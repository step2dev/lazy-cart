<?php

namespace Step2Dev\LazyCart\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Event;
use Step2Dev\LazyCart\Models\Cart;
use Step2Dev\LazyCart\Support\SessionResolver;

class CartService
{
    /**
     * @var callable
     */
    protected $discountRules;
    /**
     * @var callable
     */
    protected $priceRules;
    /**
     * @var callable
     */
    protected $shippingRules;
    protected $cart;
    /**
     * @var callable
     */
    private $priceWithDiscountRules;
    private ?string $sessionKey;

    private SessionResolver $sessionResolver;

    private ?int $authUserId;

    public function __construct()
    {
        $this->sessionResolver = new SessionResolver();
        $this->sessionKey = $this->sessionResolver->getCartSessionKey();
        $this->authUserId = auth('sanctum')->id() ?? auth()->id();
        $this->cart = null;
    }

    public function getCartId(): int
    {
        return Cart::forCurrentUser()->value('id');
    }

    protected function getCartForCurrentUser(): ?Cart
    {
        if ($this->cart === null) {
            $this->cart = Cart::forCurrentUser()->firstOrCreate([]);
        }

        return $this->cart;
    }

    public function getAuthUserId(): ?int
    {
        return $this->authUserId;
    }

    public function getSessionKey(): string
    {
        return $this->sessionKey;
    }

    public function add(Model $item, int $quantity, array $options = []): bool
    {
        $cart = $this->getCartForCurrentUser();

        Event::dispatch('cart.item.adding', [$this->cart, $item, $quantity, $options]);

        $items = $cart?->items()->create([
            'itemable_id' => $item->id,
            'itemable_type' => get_class($item),
            'quantity' => $quantity,
            'options' => $options,
        ]);

        Event::dispatch('cart.item.added', [$this->cart, $item, $quantity, $options]);

        return $items->wasRecentlyCreated;
    }

    public function update($item, int $quantity, array $options = []): bool
    {
        return Cart::forCurrentUser()
            ->items()
            ->where('itemable_id', $item->id)
            ->update(compact('quantity', 'options'));
    }

    public function remove($item)
    {
        Cart::forCurrentUser()
            ->items()
            ->where('itemable_id', $item->id)
            ->delete();
    }

    public function clear(): ?bool
    {
        return Cart::forCurrentUser()->delete();
    }

    public function getTotal()
    {
        $total = 0;

        foreach ($this->cart->items as $item) {
            $price = $item->getPrice();

            if (is_callable($this->priceRules)) {
                $price = call_user_func($this->priceRules, $price, $item);
            }
            $total += $price * $item->quantity;
        }

        return $total;
    }

    public function getTotalWithDiscount()
    {
        $total = 0;

        foreach ($this->cart->items as $item) {
            $price = $item->getPrice();

            if (is_callable($this->priceRules)) {
                $price = call_user_func($this->priceRules, $price, $item);
            }

            if (is_callable($this->priceWithDiscountRules)) {
                $price = call_user_func($this->priceWithDiscountRules, $price, $item);
            }

            $total += $price * $item->quantity;
        }

        if (is_callable($this->discountRules)) {
            $total = call_user_func($this->discountRules, $total, $this->cart);
        }

        return $total;
    }

    public function getTotalWithShipping()
    {
        $totalWithDiscount = $this->getTotalWithDiscount();
        $shippingCost = 0;

        if (is_callable($this->shippingRules)) {
            $shippingCost = call_user_func($this->shippingRules, $totalWithDiscount, $this->cart);
        }

        return $totalWithDiscount + $shippingCost;
    }

    public function setPriceRules(callable $rules)
    {
        $this->priceRules = $rules;

        return $this;
    }

    public function setPriceWithDiscountRules(callable $rules)
    {
        $this->priceWithDiscountRules = $rules;

        return $this;
    }

    public function setDiscountRules(callable $rules)
    {
        $this->discountRules = $rules;

        return $this;
    }

    public function setShippingRules(callable $rules)
    {
        $this->shippingRules = $rules;
    }
}

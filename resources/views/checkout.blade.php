{{-- {{ dd($basicProductPrices, $premiumProductPrices) }} --}}

<!DOCTYPE html>
<html>
  <head>
    <title>Subscribe to a cool new product</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
    <script src="https://js.stripe.com/v3/"></script>
  </head>
  <body>
    <section>
      <div class="product">
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="14px" height="16px" viewBox="0 0 14 16" version="1.1">
            <defs/>
            <g id="Flow" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                <g id="0-Default" transform="translate(-121.000000, -40.000000)" fill="#E184DF">
                    <path d="M127,50 L126,50 C123.238576,50 121,47.7614237 121,45 C121,42.2385763 123.238576,40 126,40 L135,40 L135,56 L133,56 L133,42 L129,42 L129,56 L127,56 L127,50 Z M127,48 L127,42 L126,42 C124.343146,42 123,43.3431458 123,45 C123,46.6568542 124.343146,48 126,48 L127,48 Z" id="Pilcrow"/>
                </g>
            </g>
        </svg>
        <div class="description">
          <h3>Starter plan</h3>
          <h5>$20.00 / month</h5>
        </div>
      </div>
      <form action="{{ route('create_checkout_session') }}" method="POST">
        @csrf
        <!-- Add a hidden field with the lookup_key of your Price -->
        <input type="hidden" name="price_id" value="{{env('STRIPE_PRICE_BASIC')}}" />
        <button id="checkout-and-portal-button" type="submit">Checkout</button>
      </form>
    </section>
  </body>
</html>
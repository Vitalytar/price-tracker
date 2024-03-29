@section('pageTitle', 'Visi produkti')
@extends('layouts/app')
@section('page-class', 'all-products')
@section('js')
    <script src="{{ asset('js/layout-switcher.js') }}"></script>
@endsection
@section('content')
    <?php
    $escapeLatvian = [
        'Ā' => 'a', 'ā' => 'a', 'Č' => 'c', 'č' => 'c', 'Ē' => 'e', 'ē' => 'e', 'Ģ' => 'g', 'ģ' => 'g', 'Ī' => 'i',
        'ī' => 'i', 'Ķ' => 'k', 'ķ' => 'k', 'Ļ' => 'l', 'ļ' => 'l', 'Ņ' => 'n', 'ņ' => 'n', 'Š' => 's', 'š' => 's',
        'Ū' => 'ū', 'ū' => 'u', 'Ž' => 'z', 'ž' => 'z'
    ]
    ?>
    <h1><?= __('Visi produkti') ?></h1>
    <div class="change-product-view">
        <i class="fas fa-th fa-2x grid-icon active" onclick="gridView()"></i>
        <i class="fas fa-list fa-2x list-icon disabled" onclick="listView()"></i>
    </div>
    <div class="all-products grid-view">
        @foreach ($products as $product)
            <?php $productLink = str_replace('--', '-', strtr(strtolower(str_replace([' ', ',', '/'], '-', $product->product_name)), $escapeLatvian)); ?>
            <div class="product-item">
                @auth
                <div class="like-item" data-product-id="<?= $product->id ?>">
                    <i class="far fa-heart fa-lg not-liked"></i>
                    <i class="fas fa-heart fa-lg liked"></i>
                </div>
                @endauth
                <a href="<?= route(
                    'product-page', [
                    'productName' => str_replace('?', '', htmlentities(utf8_decode($productLink))),
                    'productId' => $product->id
                ]
                ) ?>" class="link-to-product-page">
                    <img class="product-main-image" src="{{ asset('storage/' . $product->product_image_url) }}"
                         alt="ProductImage"
                         onerror="
                             this.onerror=null; // Handle failed image load and replace it with a placeholder image
                             this.src='<?= asset('images/placeholder.png') ?>';
                             this.className='product-main-image placeholder-image'"
                    >
                    <div class="product-name"><?= $product->product_name ?></div>
                    <form method="POST" action="<?= route('parse-product') ?>">
                        @csrf
                        <input name="productUrl" value="<?= $product->product_url ?>" type="hidden">
                        <button type="submit"><?= __('Saņemt aktuālo cenu') ?></button>
                    </form>
                    <x-product-price :productId="$product->id"/>
                    <div class="link-to-product-source">
                        <a href="<?= $product->product_url ?>" target="_blank">
                            <?= __('Avots: ') . $product->source_web ?>
                        </a>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    <script type="text/javascript">
        $('.like-item').on('click', function () {
            let currentProduct = $(this),
                productId = $(this).attr('data-product-id');

            $('<div></div>').appendTo('body')
                .html('<div><h6>' + '<?= __('Vai Jūs vēlaties ieslēgt paziņojumus par šī produkta cenas izmiņām?') ?>' + '</h6></div>')
                .dialog({
                    modal: true,
                    title: 'Ieslēgt paziņojumus',
                    zIndex: 1000,
                    autoOpen: true,
                    width: 'auto',
                    resizable: 'false',
                    buttons: {
                        Jā: function () {
                            $(this).remove();
                            addLikedItem(productId, 1, currentProduct);
                        },
                        Nē: function () {
                            addLikedItem(productId, 0, currentProduct);
                            $(this).remove();
                        }
                    }
            });
        });

        function addLikedItem(productId, notify, currentProduct) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "{{ route('save-liked-product') }}",
                data: {
                    productId: productId,
                    notify: notify
                },
                beforeSend: function () {
                    currentProduct.parent().addClass('loader');
                },
                complete: function () {
                    currentProduct.parent().removeClass('loader');
                },
                success: function (data) {
                    currentProduct.addClass('active');
                    // TODO: append message about successful save
                    console.log(data);
                }
            })
        }
    </script>
@endsection

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
                <div class="like-item" data-product-id="<?= $product->id ?>">
                    <i class="far fa-heart fa-lg not-liked"></i>
                    <i class="fas fa-heart fa-lg liked"></i>
                </div>
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
                    <div class="link-to-product-source">
                        <a href="<?= $product->product_url ?>" target="_blank">
                            <?= __('Avots: ') . $product->source_web ?>
                        </a>
                    </div>
                    <form method="POST" action="<?= route('parse-product') ?>">
                        @csrf
                        <input name="productUrl" value="<?= $product->product_url ?>" type="hidden">
                        <button type="submit"><?= __('Saņemt aktuālo cenu') ?></button>
                    </form>
                </a>
            </div>
        @endforeach
    </div>
    <script type="text/javascript">
        $('.like-item').on('click', function () {
            $(this).addClass('active');
            let productId = $(this).attr('data-product-id');

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
                        addLikedItem(productId, true);
                    },
                    Nē: function() {
                        addLikedItem(productId, false);
                        $(this).remove();
                    }
                }
            });
        });

        function addLikedItem(productId, notify) {
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
                    $(this).addClass('loader');
                },
                complete: function () {
                    $('#product-data-table').removeClass('loader');
                },
                success: function (data) {
                    console.log('success!');
                    console.log(data);
                }
            })
        }
    </script>
@endsection

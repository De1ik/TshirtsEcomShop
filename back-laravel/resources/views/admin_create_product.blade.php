@extends('layouts.layout')

@section('title', 'IgestShop - Create New Product')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/admin_create_product.css') }}" rel="stylesheet">
    <link href="{{ asset('css/layout.css') }}" rel="stylesheet">
@endsection

@section('content')
<main class="container my-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin_default_catalogue') }}">Admin Panel Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">Create new product</li>
        </ol>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <section class="row create-product-form">
        <h2>Create</h2>
        <form method="POST" action="{{ route('save_new_product') }}" enctype="multipart/form-data">
            @csrf
            <div class="row align-items-start">
                <article class="col-md-3 mb-4">
                    <div class="form-group mb-4">
                        <label for="productPhoto" class="form-label">Select photo</label>
                        <input type="file" name="productPhoto[]" class="form-control @error('productPhoto.*') is-invalid @enderror" id="productPhoto" accept="image/*" multiple>
                        @error('productPhoto.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="product-images" id="productImages"></div>
                </article>

                <article class="col-md-7 mb-4">
                    <div class="form-group mb-3">
                        <label class="form-label">Product Type</label>
                        <div class="switcher">
                            <input type="radio" name="product_type" id="new_product" value="new" {{ old('product_type', 'new') == 'new' ? 'checked' : '' }} onchange="toggleProductType()">
                            <label for="new_product" class="switcher-label">New Product</label>
                            <input type="radio" name="product_type" id="variant" value="variant" {{ old('product_type') == 'variant' ? 'checked' : '' }} onchange="toggleProductType()">
                            <label for="variant" class="switcher-label">Add Variant</label>
                        </div>
                        @error('product_type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3" id="variant_product_field" style="display: {{ old('product_type') == 'variant' ? 'block' : 'none' }};">
                        <label for="parent_product" class="form-label">Select Parent Product</label>
                        <select name="parent_product_id" class="form-select @error('parent_product_id') is-invalid @enderror" id="parent_product">
                            <option value="" disabled {{ old('parent_product_id') ? '' : 'selected' }}>Select product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ old('parent_product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3" id="product_name_field" style="display: {{ old('product_type') == 'variant' ? 'none' : 'block' }};">
                        <label for="productName" class="form-label">Name of Product</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="productName" placeholder="Enter product name" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" id="category">
                                <option value="" disabled {{ old('category') ? '' : 'selected' }}>Select category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category['name'] }}" {{ old('category') == $category['name'] ? 'selected' : '' }}>{{ ucfirst($category['name']) }}</option>
                                @endforeach
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input
                                type="number"
                                name="price"
                                class="form-control @error('price') is-invalid @enderror"
                                id="price" placeholder="Enter price"
                                value="{{ old('price') }}"
                                step="0.01"
                                min="0"
                                inputmode="decimal" >
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" name="amount" class="form-control @error('amount') is-invalid @enderror" id="amount" placeholder="Enter amount" value="{{ old('amount') }}">
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="collection" class="form-label">Collection</label>
                            <select name="collection" class="form-select @error('collection') is-invalid @enderror" id="collection" onchange="toggleNewCollectionField()">
                                <option value="" disabled {{ old('collection') ? '' : 'selected' }}>Select collection</option>
                                <option value="new" {{ old('collection') == 'new' ? 'selected' : '' }}>Create New collection</option>
                                @foreach($collections as $collection)
                                    <option value="{{ $collection->id }}" {{ old('collection') == $collection->id ? 'selected' : '' }}>{{ $collection->name }}</option>
                                @endforeach
                            </select>
                            @error('collection')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="form-group mb-4" id="new_collection_name_field" style="display: {{ old('collection') == 'new' ? 'block' : 'none' }};">
                            <label for="collectionName" class="form-label">New Collection Name</label>
                            <input type="text" name="new_collection_name" class="form-control @error('new_collection_name') is-invalid @enderror" id="collectionName" placeholder="Enter collection name" value="{{ old('new_collection_name') }}">
                            @error('new_collection_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="enableDiscount" id="enableDiscount" {{ old('enableDiscount') ? 'checked' : '' }} onchange="toggleDiscountFields()">
                            <label class="form-check-label" for="enableDiscount">Enable Discount</label>
                        </div>
                        @error('enableDiscount')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div id="discountFields" style="display: {{ old('enableDiscount') ? 'block' : 'none' }};">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="discountPrice" class="form-label">Discount Price (€)</label>
                                <input type="number" name="discount-price" class="form-control @error('discount-price') is-invalid @enderror" id="discountPrice" min="0" step="0.01" value="{{ old('discount-price') }}">
                                @error('discount-price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="discountStartDate" class="form-label">Start Date</label>
                                <input type="date" name="discount-start-date" class="form-control @error('discount-start-date') is-invalid @enderror" id="discountStartDate" value="{{ old('discount-start-date') }}">
                                @error('discount-start-date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="discountEndDate" class="form-label">End Date</label>
                                <input type="date" name="discount-end-date" class="form-control @error('discount-end-date') is-invalid @enderror" id="discountEndDate" value="{{ old('discount-end-date') }}">
                                @error('discount-end-date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <div class="d-flex">
                            <div class="form-check me-4">
                                <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="sex-men" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}>
                                <label class="form-check-label" for="sex-men">Male</label>
                            </div>
                            <div class="form-check me-4">
                                <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="sex-women" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                <label class="form-check-label" for="sex-women">Female</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="sex-unisex" value="unisex" {{ old('gender') == 'unisex' ? 'checked' : '' }}>
                                <label class="form-check-label" for="sex-unisex">Unisex</label>
                            </div>
                        </div>
                        @error('gender')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="sizes" class="form-label">Sizes</label>
                        <div class="size-selector">
                            @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @error('sizes') is-invalid @enderror" type="checkbox" name="sizes[]" id="size-{{ $size }}" value="{{ $size }}" {{ in_array($size, old('sizes', [])) ? 'checked' : '' }}>
                                    <label class="form-check-label size-label" for="size-{{ $size }}">{{ $size }}</label>
                                </div>
                            @endforeach
                        </div>
                        @error('sizes')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @error('sizes.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="color" class="form-label">Color</label>
                        <select name="color-id" class="form-select @error('color-id') is-invalid @enderror" id="variantColor" onchange="toggleNewColorField()">
                            <option value="" disabled {{ old('color-id') ? '' : 'selected' }}>Select color</option>
                            <option value="new" {{ old('color-id') == 'new' ? 'selected' : '' }}>Create New Color</option>
                            @foreach($colors as $color)
                                <option value="{{ $color->id }}" {{ old('color-id') == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                            @endforeach
                        </select>
                        @error('color-id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-4" id="new_color_name_field" style="display: {{ old('color-id') == 'new' ? 'block' : 'none' }};">
                        <label for="colorName" class="form-label">New Color Name</label>
                        <input type="text" name="new_color_name" class="form-control @error('new_color_name') is-invalid @enderror" id="colorName" placeholder="Enter color name" value="{{ old('new_color_name') }}">
                        @error('new_color_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group mb-4" id="new_color_field" style="display: {{ old('color-id') == 'new' ? 'block' : 'none' }};">
                        <label for="new_color" class="form-label">New Color</label>
                        <div class="color-picker-container d-flex">
                            <input type="color" class="form-control color-picker me-2 @error('new_color_hex') is-invalid @enderror" id="new_color" value="{{ old('new_color_hex', '#53403C') }}">
                            <input type="text" name="new_color_hex" class="form-control color-hex @error('new_color_hex') is-invalid @enderror" id="new_color_hex" placeholder="#53403C" value="{{ old('new_color_hex', '#53403C') }}" pattern="#[0-9A-Fa-f]{6}" maxlength="7">
                        </div>
                        @error('new_color_hex')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="description" class="form-label">Product Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="4" placeholder="Enter product description">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </article>

                <aside class="col-md-2 mb-4 d-flex flex-column h-100">
                    <button type="submit" class="btn btn-submit">Create</button>
                </aside>
            </div>
        </form>
    </section>
</main>

<section class="banner text-center py-4">
    <p>ENJOY EVERY MOMENT!</p>
</section>
@endsection

@section('scripts')
<script>
    const productPhotoInput = document.getElementById('productPhoto');
    const productImagesContainer = document.getElementById('productImages');
    const newColorPicker = document.getElementById('new_color');
    const newColorHex = document.getElementById('new_color_hex');

    productPhotoInput.addEventListener('change', (event) => {
        const files = event.target.files;
        for (let file of files) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const imageWrapper = document.createElement('div');
                imageWrapper.classList.add('image-wrapper');
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Uploaded Product Image';
                const deleteBtn = document.createElement('button');
                deleteBtn.type = 'button';
                deleteBtn.classList.add('delete-image-btn');
                deleteBtn.innerHTML = '×';
                deleteBtn.onclick = () => deleteImage(deleteBtn);
                imageWrapper.appendChild(img);
                imageWrapper.appendChild(deleteBtn);
                productImagesContainer.appendChild(imageWrapper);
            };
            reader.readAsDataURL(file);
        }
    });

    function deleteImage(button) {
        button.parentElement.remove();
    }

    function toggleDiscountFields() {
        const enableDiscount = document.getElementById('enableDiscount').checked;
        const discountFields = document.getElementById('discountFields');
        discountFields.style.display = enableDiscount ? 'block' : 'none';
        if (!enableDiscount) {
            document.getElementById('discountPrice').value = '';
            document.getElementById('discountStartDate').value = '';
            document.getElementById('discountEndDate').value = '';
        }
    }

    function toggleProductType() {
        const isVariant = document.getElementById('variant').checked;
        document.getElementById('variant_product_field').style.display = isVariant ? 'block' : 'none';
        document.getElementById('product_name_field').style.display = isVariant ? 'none' : 'block';
        document.getElementById('category').disabled = isVariant;
        document.getElementById('collection').disabled = isVariant;
        document.getElementById('price').disabled = isVariant;
        document.getElementById('enableDiscount').disabled = isVariant;
        document.getElementById('description').disabled = isVariant;
        document.getElementById('sex-men').disabled = isVariant;
        document.getElementById('sex-women').disabled = isVariant;
        document.getElementById('sex-unisex').disabled = isVariant;
    }

    function toggleNewColorField() {
        const colorSelect = document.getElementById('variantColor').value;
        const newColorNameField = document.getElementById('new_color_name_field');
        const newColorField = document.getElementById('new_color_field');
        if (colorSelect === 'new') {
            newColorNameField.style.display = 'block';
            newColorField.style.display = 'block';
        } else {
            newColorNameField.style.display = 'none';
            newColorField.style.display = 'none';
        }
    }

    function toggleNewCollectionField() {
        const collectionSelect = document.getElementById('collection').value;
        const newCollectionNameField = document.getElementById('new_collection_name_field');
        const newCollectionField = document.getElementById('new_collection_field');
        if (collectionSelect === 'new') {
            newCollectionNameField.style.display = 'block';
            newCollectionField.style.display = 'block';
        } else {
            newCollectionNameField.style.display = 'none';
            newCollectionField.style.display = 'none';
        }
    }

    // Initialize form state based on old input
    toggleProductType();
    toggleNewColorField();
    toggleDiscountFields();
</script>
@endsection

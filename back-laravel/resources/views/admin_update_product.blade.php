@extends('layouts.layout')

@section('title', 'IgestShop - Update Product')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/admin_update_product.css') }}" rel="stylesheet">
    <link href="{{ asset('css/layout.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
<main class="container my-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin_default_catalogue') }}">Admin Panel Products</a></li>
            <li class="breadcrumb-item active" aria-current="page">Update Product</li>
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

    <section class="create-product-form card shadow-lg">
        <div class="card-header bg-gradient-primary text-white">
            <h2 class="mb-0">Update Product</h2>
        </div>
        <div class="card-body">
            <div class="row align-items-start">
                <!-- Product Images Section -->
                <article class="col-md-3 mb-4">
                    <div class="form-group mb-4">
                        <label for="productPhoto" class="form-label">Select Photos</label>
                        <form method="POST" action="{{ route('upload_product_images', $product->id) }}" enctype="multipart/form-data" id="imageUploadForm">
                            @csrf
                            <div class="photo-upload-group">
                                <select name="color-id" class="form-select mb-2 @error('color-id') is-invalid @enderror" id="photoColor">
                                    <option value="" disabled {{ old('color-id') ? '' : 'selected' }}>Select color</option>
                                    @foreach($usedColors as $color)
                                        <option value="{{ $color->id }}" {{ old('color-id') == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                                    @endforeach
                                </select>
                                @error('color-id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <input type="file" name="productPhoto[]" class="form-control @error('productPhoto.*') is-invalid @enderror" id="productPhoto" accept="image/*" multiple>
                                @error('productPhoto.*')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <button type="submit" class="btn btn-images">Upload Images</button>
                            </div>
                        </form>
                    </div>
                    <div class="product-images" id="productImages">
                        @forelse ($product->images as $image)
                            <div class="image-wrapper mb-4 text-center" data-color="{{ $image->color->name ?? 'No color' }}">
                                <div class="color-label mb-1 fw-semibold">
                                    {{ $image->color->name ?? 'No color' }}
                                </div>
                                <img src="{{ asset('storage/product-photos/' . $image->image_url) }}" alt="Product Image" class="img-thumbnail" style="max-width: 150px;">
                                <form method="POST" action="{{ route('delete_product_image', $image->id) }}" class="delete-image-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="delete-image-btn btn btn-sm btn-danger mt-1">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p>No product images uploaded.</p>
                        @endforelse
                    </div>
                </article>

                <!-- Product Information Section -->
                <article class="col-md-7 mb-4">
                    <form method="POST" action="{{ route('update_product', $product->id) }}" id="updateProductForm">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-4" id="variant_product_field" style="display: none;">
                            <label class="form-label">Variants</label>
                            <div class="variant-list card">
                                @foreach($product->variants as $variant)
                                    <div class="variant-item card-body" onclick="selectVariant('{{ $variant->id }}')">
                                        <input type="radio" name="parent_product_id" id="variant-{{ $variant->id }}" value="{{ $variant->id }}" style="display: none;" {{ old('parent_product_id') == $variant->id ? 'checked' : '' }}>
                                        <span>{{ $variant->id }}-{{ $product->name }} (Size: {{ $variant->size ?? 'N/A' }})</span>
                                    </div>
                                @endforeach
                            </div>
                            @error('parent_product_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4" id="product_name_field">
                            <label for="productName" class="form-label">Product Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="productName" value="{{ old('name', $product->name) }}" placeholder="Enter product name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="category" class="form-label">Category</label>
                                <select name="category" class="form-select @error('category') is-invalid @enderror" id="category">
                                    <option value="hoodie" {{ old('category', $product->category) == 'hoodie' ? 'selected' : '' }}>Hoodie</option>
                                    <option value="tshirt" {{ old('category', $product->category) == 'tshirt' ? 'selected' : '' }}>T-Shirt</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label for="price" class="form-label">Price (€)</label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" id="price" placeholder="Enter price" value="{{ old('price', $product->price) }}" step="0.01">
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                @php
                                    $totalAmount = $product->variants->sum('amount');
                                @endphp
                                <label for="amount" class="form-label">Stock Amount</label>
                                <input type="number" name="amount" class="form-control" id="amount" value="{{ old('amount', $totalAmount) }}" placeholder="Enter amount" disabled>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="collection" class="form-label">Collection</label>
                                <select name="collection" class="form-select @error('collection') is-invalid @enderror" id="collection" onchange="toggleNewCollectionField()">
                                    <option value="" disabled {{ old('collection') ? '' : 'selected' }}>Select collection</option>
                                    <option value="new" {{ old('collection') == 'new' ? 'selected' : '' }}>Create New Collection</option>
                                    @foreach($collections as $collection)
                                        <option value="{{ $collection->id }}" {{ old('collection', $product->collection_id) == $collection->id ? 'selected' : '' }}>{{ $collection->name }}</option>
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

                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input @error('enableDiscount') is-invalid @enderror" type="checkbox" name="enableDiscount" id="enableDiscount" {{ old('enableDiscount', $product->is_discount) ? 'checked' : '' }} onchange="toggleDiscountFields()">
                                <label class="form-check-label" for="enableDiscount">Enable Discount</label>
                            </div>
                            @error('enableDiscount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="discountFields" style="display: {{ old('enableDiscount', $product->is_discount) ? 'block' : 'none' }};" class="card p-3 mb-4">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="discountPrice" class="form-label">Discount Price (€)</label>
                                    <input type="number" name="discount-price" class="form-control @error('discount-price') is-invalid @enderror" id="discountPrice" value="{{ old('discount-price', $product->final_price) }}" min="0" step="0.01">
                                    @error('discount-price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="discountStartDate" class="form-label">Start Date</label>
                                    <input
                                        type="date"
                                        name="discount-start-date"
                                        class="form-control @error('discount-start-date') is-invalid @enderror"
                                        value="{{ old('discount-start-date', optional($product->discount)->date_start ? \Carbon\Carbon::parse($product->discount->date_start)->format('Y-m-d') : '') }}"
                                        id="discountStartDate"
                                    >
                                    @error('discount-start-date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="discountEndDate" class="form-label">End Date</label>
                                    <input
                                        type="date"
                                        name="discount-end-date"
                                        class="form-control @error('discount-end-date') is-invalid @enderror"
                                        value="{{ old('discount-end-date', optional($product->discount)->date_end ? \Carbon\Carbon::parse($product->discount->date_end)->format('Y-m-d') : '') }}"
                                        id="discountEndDate"
                                    >
                                    @error('discount-end-date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Gender</label>
                            <div class="d-flex flex-wrap">
                                <div class="form-check me-4 mb-2">
                                    <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="sex-men" value="male" {{ old('gender', $product->gender) == 'male' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sex-men">Male</label>
                                </div>
                                <div class="form-check me-4 mb-2">
                                    <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="sex-women" value="female" {{ old('gender', $product->gender) == 'female' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sex-women">Female</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input @error('gender') is-invalid @enderror" type="radio" name="gender" id="sex-unisex" value="unisex" {{ old('gender', $product->gender) == 'unisex' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sex-unisex">Unisex</label>
                                </div>
                            </div>
                            @error('gender')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="description" class="form-label">Product Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="5" placeholder="Enter product description">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button for Product Update -->
                        <button type="submit" class="btn btn-update">
                            <i class="bi bi-check-circle me-2"></i>
                            Update Product
                        </button>
                    </form>

                    <!-- Variant Section -->
                    <div class="variant-section mt-5">
                        <h4 class="mb-3">Product Variants</h4>
                        <button type="button" class="btn btn-add-variant mb-3" onclick="createVariant(this)">
                            <i class="bi bi-plus-circle me-2"></i>Add Variant
                        </button>
                        <div class="table-responsive">
                            <table class="table table-hover variant-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>SKU</th>
                                        <th>Size</th>
                                        <th>Color</th>
                                        <th>Stock</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="variantTableBody">
                                    @forelse ($product->variants as $variant)
                                        <tr id="variant-row-{{ $variant->id }}">
                                            <td>{{ $variant->id }}</td>
                                            <td>{{ $variant->size }}</td>
                                            <td>{{ $variant->color->name }}</td>
                                            <td>{{ $variant->amount }}</td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-edit-variant btn-sm"
                                                    data-sku="{{ $variant->id }}"
                                                    data-size="{{ $variant->size }}"
                                                    data-color="{{ $variant->color_id }}"
                                                    data-stock="{{ $variant->amount }}"
                                                    onclick="editVariant(this)">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button
                                                    type="button"
                                                    class="btn btn-delete-variant btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#universalDeleteModal"
                                                    data-id="{{ $variant->id }}"
                                                    data-type="variant"
                                                    data-name="variant #{{ $variant->id }}"
                                                    data-url="{{ route('delete_variant', $variant->id) }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <p>No variants were created.</p>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </article>

                <!-- Action Buttons -->
                <aside class="col-md-2 mb-4">
                    <div class="d-flex flex-column h-100 gap-2">
                        <button
                            type="button"
                            class="btn btn-delete"
                            data-bs-toggle="modal"
                            data-bs-target="#universalDeleteModal"
                            data-id="{{ $product->id }}"
                            data-type="product"
                            data-name="product '{{ $product->name }}'"
                            data-url="{{ route('delete_product', $product->id) }}">
                            <i class="bi bi-trash me-2"></i>
                        </button>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <!-- Variant Modal -->
    <div class="modal fade" id="variantModal" tabindex="-1" aria-labelledby="variantModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title" id="variantModalLabel">Add Variant</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('update_variant', $product->id) }}" id="variantForm">
                        @csrf
                        @method('PUT')
                        <div class="mb-3" id="sku-block">
                            <label for="variantSku" class="form-label">SKU</label>
                            <input type="number" class="form-control @error('sku') is-invalid @enderror" id="variantSku" name="sku" readonly>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="size-create-variant">
                            <label for="variantSizeCreate" class="form-label">Size</label>
                            <select class="form-select @error('size') is-invalid @enderror" name="size" id="variantSizeCreate" required>
                                <option value="" disabled {{ old('color-id') ? '' : 'selected' }}>Select size</option>

                                <option value="XS" {{ old('size') == 'XS' ? 'selected' : '' }}>XS</option>
                                <option value="S" {{ old('size') == 'S' ? 'selected' : '' }}>S</option>
                                <option value="M" {{ old('size') == 'M' ? 'selected' : '' }}>M</option>
                                <option value="L" {{ old('size') == 'L' ? 'selected' : '' }}>L</option>
                                <option value="XL" {{ old('size') == 'XL' ? 'selected' : '' }}>XL</option>
                                <option value="XXL" {{ old('size') == 'XXL' ? 'selected' : '' }}>XXL</option>
                            </select>
                            @error('size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="size-update-variant">
                            <label for="variantSizeUpdate" class="form-label">Size</label>
                            <input
                                   type="text"
                                   class="form-control @error('size') is-invalid @enderror"
                                   id="variantSizeUpdate"
                                   value="{{ old('size') }}"
                                   readonly>
                            @error('size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                        <div class="mb-3">
                            <label for="variantStock" class="form-label">Stock</label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror" id="variantStock" min="0" name="stock" placeholder="Enter stock quantity" value="{{ old('stock') }}" required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <label for="color" class="form-label">Color</label>
                            <select name="color-id" class="form-select @error('color-id') is-invalid @enderror" id="variantColor" onchange="toggleNewColorField()">
                                <option value="" disabled {{ old('color-id') ? '' : 'selected' }}>Select color</option>
                                @foreach($colors as $color)
                                    <option value="{{ $color->id }}" {{ old('color-id') == $color->id ? 'selected' : '' }}>{{ $color->name }}</option>
                                @endforeach
                                <option value="new" {{ old('color-id') == 'new' ? 'selected' : '' }}>Create New Color</option>
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
                        <div class="modal-footer">
                            <button type="button" class="btn btn-no" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-confirm" id="saveVariantBtn">Save Variant</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Universal Delete Modal -->
    <div class="modal fade" id="universalDeleteModal" tabindex="-1" aria-labelledby="universalDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title" id="universalDeleteModalLabel">Delete Confirmation</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="universalDeleteModalBody">
                    Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                    <form method="POST" id="universalDeleteForm" action="{{ route('delete_product', $product->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-no" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-confirm">Delete <i class="bi bi-trash ms-1"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<section class="banner text-center py-4">
    <p>ENJOY EVERY MOMENT!</p>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Image Upload Handling with Color Association
    const productPhotoInput = document.getElementById('productPhoto');
    const photoColorSelect = document.getElementById('photoColor');
    const productImagesContainer = document.getElementById('productImages');

    productPhotoInput.addEventListener('change', (event) => {
        const files = event.target.files;
        const selectedColorId = photoColorSelect.value;

        if (!selectedColorId) {
            alert('Please select a color for the photos.');
            productPhotoInput.value = '';
            return;
        }
    });

    // Discount Fields Toggle
    function toggleDiscountFields() {
        const enableDiscount = document.getElementById('enableDiscount').checked;
        const discountFields = document.getElementById('discountFields');
        discountFields.style.display = enableDiscount ? 'block' : 'none';
    }

    // Product Type Toggle
    function toggleProductType() {
        const isVariant = document.getElementById('variant')?.checked;
        document.getElementById('variant_product_field').style.display = isVariant ? 'block' : 'none';
        document.getElementById('product_name_field').style.display = isVariant ? 'none' : 'block';
    }

    // Color Field Toggle
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

    // Collection Field Toggle
    function toggleNewCollectionField() {
        const collectionSelect = document.getElementById('collection').value;
        const newCollectionNameField = document.getElementById('new_collection_name_field');
        if (collectionSelect === 'new') {
            newCollectionNameField.style.display = 'block';
        } else {
            newCollectionNameField.style.display = 'none';
        }
    }

    window.editVariant = function(button) {
        document.getElementById('sku-block').style.display = 'block';

        document.getElementById('size-update-variant').style.display = 'block';
        document.getElementById('variantSizeCreate').setAttribute('disabled', 'disabled');

        document.getElementById('size-create-variant').style.display = 'none';

        document.getElementById('variantSku').value = button.dataset.sku;
        document.getElementById('variantSizeUpdate').value = button.dataset.size;
        document.getElementById('variantColor').value = button.dataset.color;
        document.getElementById('variantStock').value = button.dataset.stock;
        document.getElementById('variantModalLabel').textContent = 'Edit Variant';
        new bootstrap.Modal(document.getElementById('variantModal')).show();
    };

    window.createVariant = function(button) {
        document.getElementById('sku-block').style.display = 'none';

        document.getElementById('size-update-variant').style.display = 'none';

        document.getElementById('size-create-variant').style.display = 'block';
        document.getElementById('variantSizeCreate').removeAttribute('disabled');
        document.getElementById('variantSizeCreate').setAttribute('required', 'required');

        document.getElementById('variantSku').value = '';
        document.getElementById('variantSizeCreate').value = '';
        document.getElementById('variantColor').value = '';
        document.getElementById('variantStock').value = '';
        document.getElementById('variantModalLabel').textContent = 'Create Variant';
        new bootstrap.Modal(document.getElementById('variantModal')).show();
    };

    const universalModal = document.getElementById('universalDeleteModal');
    universalModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const itemName = button.getAttribute('data-name');
        const actionUrl = button.getAttribute('data-url');

        const modalBody = universalModal.querySelector('#universalDeleteModalBody');
        const deleteForm = universalModal.querySelector('#universalDeleteForm');

        modalBody.textContent = `Are you sure you want to delete ${itemName}?`;
        deleteForm.setAttribute('action', actionUrl);
    });

    // Initialize form state based on old input
    toggleDiscountFields();
    toggleNewColorField();
    toggleNewCollectionField();
</script>
@endsection

@extends('layouts.layout')

@section('title', 'IgestShop - Create New Product')

@section('styles')
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link href="{{ asset('css/admin_create_product.css') }}" rel="stylesheet">
  <link href="{{ asset('css/layout.css') }}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
@endsection

@section('content')
<main class="container my-5">
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
      <li class="breadcrumb-item"><a href="#">Admin Panel Products</a></li>
      <li class="breadcrumb-item active" aria-current="page">Create New Product</li>
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

  <section class="create-product-form card shadow-lg">
    <div class="card-header bg-gradient-primary text-white">
      <h2 class="mb-0">Create New Product</h2>
    </div>
    <div class="card-body">
      <form method="POST" action="{{ route('update_product', $product->id) }}" enctype="multipart/form-data" id="updateProductForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="photo_colors" id="photoColors" value="">
        <div class="row align-items-start">
          <!-- Product Images Section -->
          <article class="col-md-3 mb-4">
            <div class="form-group mb-4">
              <label for="productPhoto" class="form-label">Select Photos</label>
              <div class="photo-upload-group">
                <input type="file" name="productPhoto[]" class="form-control" id="productPhoto" accept="image/*" multiple>
                  <select name="color-id" class="form-select mt-2" id="photoColor" onchange="toggleNewColorField()">
                    <option value="" disabled selected>Select color</option>
                    @foreach($colors as $color)
                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                    @endforeach
                  </select>
              </div>
            </div>
            <div class="product-images" id="productImages">
              @forelse ($product->images as $image)
                <div class="image-wrapper mb-3" data-color="{{ $image->color->name ?? 'No color' }}">
                  <img src="{{ asset('storage/' . $image->image_url) }}" alt="Product Image" class="img-thumbnail" style="max-width: 150px;">
                  <span class="color-badge">{{ $image->color->name ?? 'No color' }}</span>
                  <button type="button" class="delete-image-btn btn btn-sm btn-danger mt-1" onclick="deleteImage(this)">
                    <i class="bi bi-x"></i>
                  </button>
                </div>
              @empty
                <p>No product images uploaded.</p>
              @endforelse
            </div>
          </article>

          <!-- Product Information Section -->
          <article class="col-md-7 mb-4">

            <div class="form-group mb-4" id="variant_product_field" style="display: none;">
              <label class="form-label">Variants</label>
              <div class="variant-list card">
                @foreach($product->variants as $variant)
                  <div class="variant-item card-body" onclick="selectVariant('{{ $product->id }}')">
                    <input type="radio" name="parent_product_id" id="variant-{{ $product->id }}" value="{{ $product->id }}" style="display: none;">
                    <span>{{ $product->id }}-{{ $product->name }} (Size: {{ $product->size ?? 'N/A' }})</span>
                  </div>
                @endforeach
              </div>
            </div>

            <div class="form-group mb-4" id="product_name_field">
              <label for="productName" class="form-label">Product Name</label>
              <input type="text" name="name" class="form-control" id="productName" value="{{$product->name}}" placeholder="Enter product name">
            </div>

            <div class="row">
              <div class="col-md-6 mb-4">
                <label for="category" class="form-label">Category</label>
                <select name="category" class="form-select" id="category">
                  <option value="hoodie" {{ $product->category == 'hoodie' ? 'selected' : '' }}>Hoodie</option>
                  <option value="tshirt" {{ $product->category == 'tshirt' ? 'selected' : '' }}>T-Shirt</option>
                </select>
              </div>
              <div class="col-md-6 mb-4">
                <label for="price" class="form-label">Price (€)</label>
                <input type="number" name="price" class="form-control" id="price" placeholder="Enter price" value={{$product->price}} step="0.01">
              </div>
            </div>

            <div class="row">
              <div class="col-md-6 mb-4">
                <label for="collection" class="form-label">Collection</label>
                <select name="collection" class="form-select" id="collection">
                  @foreach($collections as $collection)
                    <option value="{{ $collection->id }}" {{ $product->collection->id == $collection->id ? 'selected' : '' }}>{{ $collection->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6 mb-4">
                  @php
                    $totalAmount = $product->variants->sum('amount');
                  @endphp
                <label for="amount" class="form-label">Stock Amount</label>
                <input type="number" name="amount" class="form-control" id="amount" value={{$totalAmount}} placeholder="Enter amount" disabled>
              </div>
            </div>

            <div class="mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="enableDiscount" id="enableDiscount" {{ $product->is_discount ? 'checked' : '' }} onchange="toggleDiscountFields()">
                <label class="form-check-label" for="enableDiscount">Enable Discount</label>
              </div>
            </div>

            <div id="discountFields" style="{{ $product->is_discount ? '' : 'display: none;' }}" class="card p-3 mb-4">
              <div class="row">
                <div class="col-md-4 mb-3">
                  <label for="discountPrice" class="form-label">Discount Price (€)</label>
                  <input type="number" name="discount-price" class="form-control" id="discountPrice" value={{$product->final_price}} min="0" step="0.01">
                </div>
                <div class="col-md-4 mb-3">
                  <label for="discountStartDate" class="form-label">Start Date</label>
                    <input
                      type="date"
                      name="discount-start-date"
                      class="form-control"
                      value="{{ optional($product->discount)->date_start ? \Carbon\Carbon::parse($product->discount->date_start)->format('Y-m-d') : '' }}"
                      id="discountStartDate"
                    >
                </div>
                <div class="col-md-4 mb-3">
                  <label for="discountEndDate" class="form-label">End Date</label>
                    <input
                      type="date"
                      name="discount-end-date"
                      class="form-control"
                      value="{{ optional($product->discount)->date_end ? \Carbon\Carbon::parse($product->discount->date_end)->format('Y-m-d') : '' }}"
                      id="discountEndDate">
                </div>
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label">Gender</label>
              <div class="d-flex flex-wrap">
                <div class="form-check me-4 mb-2">
                  <input class="form-check-input" type="radio" name="gender" id="sex-men" value="male" @checked($product->gender === 'male')>
                  <label class="form-check-label" for="sex-men">Male</label>
                </div>
                <div class="form-check me-4 mb-2">
                  <input class="form-check-input" type="radio" name="gender" id="sex-women" value="female" @checked($product->gender === 'female')>
                  <label class="form-check-label" for="sex-women">Female</label>
                </div>
                <div class="form-check mb-2">
                  <input class="form-check-input" type="radio" name="gender" id="sex-unisex" value="unisex" @checked($product->gender === 'unisex')>
                  <label class="form-check-label" for="sex-unisex">Unisex</label>
                </div>
              </div>
            </div>

            <div class="form-group mb-4">
              <label for="description" class="form-label">Product Description</label>
              <textarea class="form-control" name="description" id="description" rows="5" placeholder="Enter product description">{{$product->description}}</textarea>
            </div>

            <!-- Variant Section -->
            <div class="variant-section mt-5">
              <h4 class="mb-3">Product Variants</h4>
              <button
                type="button"
                class="btn btn-add-variant mb-3"
                data-bs-toggle="modal"
                data-bs-target="#variantModal"
                onclick="createVariant(this)"
              >
                <i class="bi bi-plus-circle me-2">
                    </i>Add Variant
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
                    @foreach ($product->variants as $variant)
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

                                <button type="button"
                                    class="btn btn-delete-variant btn-sm"
                                    data-id="{{ $variant->id }}"
                                    onclick="deleteVariant(this)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              <button type="button" class="btn btn-save-variants"><i class="bi bi-save me-2"></i>Save Variants</button>
            </div>
          </article>

          <!-- Action Buttons -->
          <aside class="col-md-2 mb-4">
            <div class="d-flex flex-column h-100 gap-2">
              <button type="submit" class="btn btn-update"><i class="bi bi-check-circle me-2"></i>Create Product</button>
              <button type="button" class="btn btn-delete" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal"><i class="bi bi-trash me-2"></i>Delete Product</button>
            </div>
          </aside>
        </div>
      </form>
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
            <div class="mb-3">
                <label for="variantStock" class="form-label">SKU</label>
                <input type="number" class="form-control" id="variantSku"  disabled required>
            </div>
            <div class="mb-3">
              <label for="variantSize" class="form-label">Size</label>
              <select class="form-select" name="size" id="variantSize" required>
                <option value="" disabled selected>Select size</option>
                <option value="XS">XS</option>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
                <option value="XXL">XXL</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="variantStock" class="form-label">Stock</label>
              <input type="number" class="form-control" id="variantStock" min="0" name="sku" placeholder="Enter stock quantity" required>
            </div>
            <div class="form-group mb-4">
              <label for="color" class="form-label">Color</label>
              <select name="color-id" class="form-select" id="variantColor" onchange="toggleNewColorField()">
                <option value="" disabled selected>Select color</option>
                @foreach($colors as $color)
                  <option value="{{ $color->id }}">{{ $color->name }}</option>
                @endforeach
                <option value="new">Create New Color</option>
              </select>
            </div>

            <div class="form-group mb-4" id="new_color_name_field" style="display: none;">
              <label for="colorName" class="form-label">New Color Name</label>
              <input type="text" name="new_color_name" class="form-control" id="colorName" placeholder="Enter color name">
            </div>

            <div class="form-group mb-4" id="new_color_field" style="display: none;">
              <label for="new_color" class="form-label">New Color</label>
              <div class="color-picker-container d-flex">
                <input type="color" class="form-control color-picker me-2" id="new_color" value="#53403C">
                <input type="text" name="new_color_hex" class="form-control color-hex" id="new_color_hex" placeholder="#53403C" value="#53403C" pattern="#[0-9A-Fa-f]{6}" maxlength="7">
              </div>
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

  <!-- Delete Confirmation Modal -->
  <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-gradient-primary text-white">
          <h5 class="modal-title" id="deleteConfirmationModalLabel">Delete Confirmation</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to delete this product?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-no" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-confirm-delete">Delete Product <i class="bi bi-trash ms-1"></i></button>
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
  let photoColors = [];

  function updatePhotoColorsInput() {
    console.log('photo_colors '+photoColors)
    document.getElementById('photoColors').value = JSON.stringify(photoColors);
  }

document.querySelectorAll('#productImages .image-wrapper').forEach(wrapper => {
  const img = wrapper.querySelector('img');
  const colorName = wrapper.dataset.color;

  console.log("Path " + img.src)
  console.log("Color Name " + colorName)

  photoColors.push({
    src: img.src,
    color: colorName // не ID, только текстовое имя
  });
});

updatePhotoColorsInput();


  productPhotoInput.addEventListener('change', (event) => {
    const files = event.target.files;
    const selectedColor = photoColorSelect.value;
    const selectedColorId = photoColorSelect.value;
    const selectedColorName = photoColorSelect.options[photoColorSelect.selectedIndex].text;


    if (!selectedColorId) {
      alert('Please select a color for the photos.');
      productPhotoInput.value = ''; // Clear the input
      return;
    }

    for (let file of files) {
      const reader = new FileReader();
      reader.onload = (e) => {
        const imageWrapper = document.createElement('div');
        imageWrapper.classList.add('image-wrapper');
        imageWrapper.dataset.color = selectedColorName;

        const img = document.createElement('img');
        img.src = e.target.result;
        img.alt = 'Uploaded Product Image';

        const colorBadge = document.createElement('span');
        colorBadge.classList.add('color-badge');
        colorBadge.textContent = selectedColorName;

        const deleteBtn = document.createElement('button');
        deleteBtn.type = 'button';
        deleteBtn.classList.add('delete-image-btn');
        deleteBtn.innerHTML = '<i class="bi bi-x"></i>';
        deleteBtn.onclick = () => deleteImage(deleteBtn);

        imageWrapper.appendChild(img);
        imageWrapper.appendChild(colorBadge);
        imageWrapper.appendChild(deleteBtn);
        productImagesContainer.appendChild(imageWrapper);

        photoColors.push({ src: e.target.result, color: selectedColorId });
        updatePhotoColorsInput();
      };
      reader.readAsDataURL(file);
    }
    productPhotoInput.value = ''; // Reset file input
    photoColorSelect.value = ''; // Reset color select
  });

  function deleteImage(button) {
    const imageWrapper = button.parentElement;
    const src = imageWrapper.querySelector('img').src;
    photoColors = photoColors.filter(photo => photo.src !== src);
    imageWrapper.remove();
    updatePhotoColorsInput();
  }

  // Discount Fields Toggle
  function toggleDiscountFields() {
    const enableDiscount = document.getElementById('enableDiscount').checked;
    const discountFields = document.getElementById('discountFields');
    discountFields.style.display = enableDiscount ? 'block' : 'none';
  }

  // Product Type Toggle
  function toggleProductType() {
    const isVariant = document.getElementById('variant').checked;
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


  document.getElementById('saveVariantBtn').addEventListener('click', () => {
    const sku = document.getElementById('variantSku').value;
    const size = document.getElementById('variantSize').value;
    const color = document.getElementById('variantColor').value;
    const stock = parseInt(document.getElementById('variantStock').value);
    const price = parseFloat(document.getElementById('variantPrice').value);

    if (!sku || !size || !color || isNaN(stock) || stock < 0 || isNaN(price) || price <= 0) {
      alert('Please fill in all fields correctly.');
      return;
    }

    const variant = { sku, size, color, stock, price };
    if (editIndex === -1) {
      variants.push(variant);
    } else {
      variants[editIndex] = variant;
    }

    bootstrap.Modal.getInstance(document.getElementById('variantModal')).hide();
  });

    window.editVariant = function(button) {
      document.getElementById('variantSku').value = button.dataset.sku;
      document.getElementById('variantSize').value = button.dataset.size;
      document.getElementById('variantColor').value = button.dataset.color;
      document.getElementById('variantStock').value = button.dataset.stock;
      document.getElementById('variantModalLabel').textContent = 'Edit Variant';
      new bootstrap.Modal(document.getElementById('variantModal')).show();
    };

    window.createVariant = function(button) {
      document.getElementById('variantSku').value = button.dataset.sku;
      document.getElementById('variantSize').value = '';
      document.getElementById('variantColor').value ='';
      document.getElementById('variantStock').value = '';
      document.getElementById('variantModalLabel').textContent = 'Edit Variant';
      new bootstrap.Modal(document.getElementById('variantModal')).show();
    };

window.deleteVariant = function(button) {
  const variantId = button.dataset.id;

  if (confirm('Are you sure you want to delete this variant?')) {
    // Удалить строку из DOM
    const row = document.getElementById('variant-row-' + variantId);
    if (row) row.remove();
    }
};

  document.querySelector('.btn-save-variants').addEventListener('click', () => {
    console.log('Saving variants:', variants);
    alert('Variants saved successfully!');
  });


</script>
@endsection

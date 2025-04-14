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
      <li class="breadcrumb-item"><a href="#">Admin Panel Products</a></li>
      <li class="breadcrumb-item active" aria-current="page">Create new product</li>
    </ol>
  </nav>

  @if(session('success'))
      <div class="alert alert-success">
          {{ session('success') }}
      </div>
  @elseif(session('error'))
      <div class="alert alert-danger">
          {{ session('error') }}
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
            <input type="file" name="productPhoto[]" class="form-control" id="productPhoto" accept="image/*" multiple>
          </div>
          <div class="product-images" id="productImages"></div>
        </article>

        <article class="col-md-7 mb-4">
          <div class="form-group mb-3">
            <label class="form-label">Product Type</label>
            <div class="switcher">
              <input type="radio" name="product_type" id="new_product" value="new" checked onchange="toggleProductType()">
              <label for="new_product" class="switcher-label">New Product</label>
              <input type="radio" name="product_type" id="variant" value="variant" onchange="toggleProductType()">
              <label for="variant" class="switcher-label">Add Variant</label>
            </div>
          </div>

          <div class="form-group mb-3" id="variant_product_field" style="display: none;">
            <label for="parent_product" class="form-label">Select Parent Product</label>
            <select name="parent_product_id" class="form-select" id="parent_product">
              <option value="" disabled selected>Select product</option>
              @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group mb-3" id="product_name_field">
            <label for="productName" class="form-label">Name of Product</label>
            <input type="text" name="name" class="form-control" id="productName" value="Test product" placeholder="Enter product name">
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="category" class="form-label">Category</label>
              <select name="category" class="form-select" id="category">
                <option value="" disabled selected>Select category</option>
                <option value="hoodie">Hoodie</option>
                <option value="tshirt">T-Shirt</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="price" class="form-label">Price</label>
              <input type="number" name="price" class="form-control" id="price" value="100" placeholder="Enter price">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="collection" class="form-label">Collection</label>
              <select name="collection" class="form-select" id="collection">
                <option value="" disabled selected>Select collection</option>
                @foreach($collections as $collection)
                    <option value="{{ $collection->id }}" {{ old('collection') == $collection->id ? 'selected' : '' }}>{{ $collection->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="amount" class="form-label">Amount</label>
              <input type="number" name="amount" class="form-control" id="amount" value="5" placeholder="Enter amount">
            </div>
          </div>

          <div class="mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="enableDiscount" id="enableDiscount" onchange="toggleDiscountFields()">
              <label class="form-check-label" for="enableDiscount">Enable Discount</label>
            </div>
          </div>

          <div id="discountFields" style="display: none;">
            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="discountPrice" class="form-label">Discount Price (€)</label>
                <input type="number" name="discount-price" class="form-control" id="discountPrice" min="0" step="0.01">
              </div>
              <div class="col-md-4 mb-3">
                <label for="discountStartDate" class="form-label">Start Date</label>
                <input type="date" name="discount-start-date" class="form-control" id="discountStartDate">
              </div>
              <div class="col-md-4 mb-3">
                <label for="discountEndDate" class="form-label">End Date</label>
                <input type="date" name="discount-end-date" class="form-control" id="discountEndDate">
              </div>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Gender</label>
            <div class="d-flex">
              <div class="form-check me-4">
                <input class="form-check-input" type="radio" name="gender" id="sex-men" value="male">
                <label class="form-check-label" for="sex-men">Male</label>
              </div>
              <div class="form-check me-4">
                <input class="form-check-input" type="radio" name="gender" id="sex-women" value="female">
                <label class="form-check-label" for="sex-women">Female</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="gender" id="sex-unisex" value="unisex">
                <label class="form-check-label" for="sex-unisex">Unisex</label>
              </div>
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="sizes" class="form-label">Sizes</label>
            <div class="size-selector">
              @foreach(['XS', 'S', 'M', 'L', 'XL', 'XXL'] as $size)
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox" name="sizes[]" id="size-{{ $size }}" value="{{ $size }}">
                  <label class="form-check-label size-label" for="size-{{ $size }}">{{ $size }}</label>
                </div>
              @endforeach
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="color" class="form-label">Color</label>
            <select name="color-id" class="form-select" id="color" onchange="toggleNewColorField()">
              <option value="" disabled selected>Select color</option>
              @foreach($colors as $color)
                <option value="{{ $color->id }}">{{ $color->name }}</option>
              @endforeach
              <option value="new">Create New Color</option>
            </select>
          </div>

          <div class="form-group mb-3" id="new_color_name_field" style="display: none;">
            <label for="colorName" class="form-label">Name of Color</label>
            <input type="text" name="new_color_name" class="form-control" id="colorName" placeholder="Enter color name">
          </div>


          <div class="form-group mb-3" id="new_color_field" style="display: none;">
            <label for="new_color" class="form-label">New Color</label>
            <div class="color-picker-container">
              <input type="color" class="form-control color-picker" id="new_color" value="#53403C">
              <input type="text" name="new_color_hex" class="form-control color-hex" id="new_color_hex" placeholder="#53403C" value="#53403C" pattern="#[0-9A-Fa-f]{6}" maxlength="7">
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="description" class="form-label">Product Description</label>
            <textarea class="form-control" name="description" id="description" rows="4" placeholder="Enter product description" value="Test Description"></textarea>
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
    const colorSelect = document.getElementById('color');
    const newColorField = document.getElementById('new_color_field');
    const newColorNameField = document.getElementById('new_color_name_field');
    newColorField.style.display = colorSelect.value === 'new' ? 'block' : 'none';
    newColorNameField.style.display = colorSelect.value === 'new' ? 'block' : 'none';
//     if (colorSelect.value !== 'new') {
//       if (newColorPicker) newColorPicker.value = '#53403C';
//       if (newColorHex) newColorHex.value = '#53403C';
//     }
  }

  // Sync new color picker and hex input
  if (newColorPicker && newColorHex) {
    newColorPicker.addEventListener('input', () => {
      newColorHex.value = newColorPicker.value.toUpperCase();
    });

    newColorHex.addEventListener('input', () => {
      const hex = newColorHex.value.toUpperCase();
      if (/^#[0-9A-F]{6}$/i.test(hex)) {
        newColorPicker.value = hex;
      }
    });
  }

  // Initialize form state
  toggleProductType();
</script>
@endsection

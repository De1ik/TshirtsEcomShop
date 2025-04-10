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

  <section class="row create-product-form">
    <h2>Create</h2>
    <form method="POST" action="{{ route('save_new_product') }}" enctype="multipart/form-data">
        @csrf
      <div class="row align-items-start">
        <article class="col-md-3 mb-4">
          <div class="form-group mb-4">
            <label for="productPhoto" class="form-label">Select photo</label>
            <input type="file" name="image" class="form-control" id="productPhoto" accept="image/*" multiple>
          </div>
          <div class="product-images" id="productImages"></div>
        </article>

        <article class="col-md-7 mb-4">
          <div class="form-group mb-3">
            <label for="productName" class="form-label">Name of Product</label>
            <input type="text" name="name" class="form-control" id="productName" placeholder="Enter product name">
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="category" class="form-label">Category</label>
              <select class="form-select" id="category">
                <option value="" disabled selected>Select category</option>
                <option value="hoodie">Hoodie</option>
                <option value="t-shirt">T-Shirt</option>
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="price" class="form-label">Price</label>
              <input type="number" name="price" class="form-control" id="price" placeholder="Enter price">
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-3">
              <label for="collection" class="form-label">Collection</label>
              <select class="form-select" id="collection">
                <option value="" disabled selected>Select collection</option>
                @foreach($collections as $collection)
                    <option value="{{ $collection->id }}" {{ old('collection') == $collection->id ? 'selected' : '' }}>{{ $collection->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 mb-3">
              <label for="amount" class="form-label">Amount</label>
              <input type="number" name="amount" class="form-control" id="amount" placeholder="Enter amount">
            </div>
          </div>

          <div class="mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="enableDiscount" onchange="toggleDiscountFields()">
              <label class="form-check-label" for="enableDiscount">Enable Discount</label>
            </div>
          </div>

          <div id="discountFields" style="display: none;">
            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="discountPrice" class="form-label">Discount Price (€)</label>
                <input type="number" name="discount" class="form-control" id="discountPrice" min="0" step="0.01">
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
                <input class="form-check-input" type="radio" name="sex" id="sex-men" value="men">
                <label class="form-check-label" for="sex-men">Men</label>
              </div>
              <div class="form-check me-4">
                <input class="form-check-input" type="radio" name="sex" id="sex-women" value="women">
                <label class="form-check-label" for="sex-women">Women</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="sex" id="sex-unisex" value="unisex">
                <label class="form-check-label" for="sex-unisex">Unisex</label>
              </div>
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="description" class="form-label">Product Description</label>
            <textarea class="form-control" id="description" rows="4" placeholder="Enter product description"></textarea>
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

  const updateProductForm = document.getElementById('createProductForm');
  updateProductForm.addEventListener('submit', (event) => {
    event.preventDefault();
    const enableDiscount = document.getElementById('enableDiscount').checked;
    if (enableDiscount) {
      const price = parseFloat(document.getElementById('price').value);
      const discountPrice = parseFloat(document.getElementById('discountPrice').value);
      const startDate = document.getElementById('discountStartDate').value;
      const endDate = document.getElementById('discountEndDate').value;
      const currentDate = new Date('2025-03-28');

      if (isNaN(discountPrice) || discountPrice <= 0) {
        alert('Please enter a valid discount price.');
        return;
      }
      if (discountPrice >= price) {
        alert('Discount price must be less than the original price.');
        return;
      }
      if (!startDate || !endDate) {
        alert('Please provide both start and end dates for the discount.');
        return;
      }
      const start = new Date(startDate);
      const end = new Date(endDate);
      if (start > end) {
        alert('Start date cannot be after the end date.');
        return;
      }
      if (start < currentDate) {
        alert('Start date cannot be in the past.');
        return;
      }
    }
    alert('Product updated successfully!');
  });
</script>
@endsection

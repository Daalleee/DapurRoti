<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>DapurRoti - Toko Roti Pilihan</title>
  <link rel="stylesheet" href="{{ asset('styles.css') }}">
  <style>
    /* Menambahkan styling ekstra jika diperlukan */
    body {
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    }
  </style>
</head>
<body>
  <header class="site-header">
    <div class="nav">
      <div class="brand">
        <div class="logo">DapurRoti</div>
        <button class="category-btn">Kategori</button>
      </div>

      <form class="search">
        <input type="search" placeholder="Cari di DapurRoti" />
        <button class="search-btn" aria-label="Cari"></button>
      </form>

      <div class="actions">
        @auth
          @if(auth()->user()->isAdmin())
            <a href="{{ route('dashboard') }}" class="btn ghost">Dashboard</a>
          @else
            <a href="{{ route('home') }}" class="btn ghost">Beranda</a>
          @endif
        @else
          <a href="{{ route('login') }}" class="btn ghost">Masuk</a>
          <a href="{{ route('register') }}" class="btn primary">Daftar</a>
        @endauth
      </div>
    </div>
  </header>

  <main class="container">
    <nav class="tabs">
      <button class="tab active">For You</button>
      <button class="tab">Produk Incaranmu</button>
    </nav>

    <section class="product-grid">
      <!-- repeat product-card as needed -->
      <article class="product-card">
        <div class="media">
          <span class="badge">32%</span>
          <div class="image" style="background-image:url('https://via.placeholder.com/400x300/FFD700/000000?text=Roti+Manis')"></div>
        </div>
        <div class="info">
          <h3 class="title">Roti Manis Isi Kacang ...</h3>
          <div class="price">Rp18.000</div>
          <div class="meta">
            <span class="rating">★ 4.9</span>
            <span class="sold">• 750+ terjual</span>
          </div>
          <div class="seller">DapurRoti Pusat</div>
        </div>
      </article>

      <article class="product-card">
        <div class="media">
          <span class="badge hot">72%</span>
          <div class="image" style="background-image:url('https://via.placeholder.com/400x300/F4A460/000000?text=Bolu+Kukus')"></div>
        </div>
        <div class="info">
          <h3 class="title">Bolu Kukus Pelangi ...</h3>
          <div class="price">Rp12.888</div>
          <div class="meta">
            <span class="rating">★ 4.7</span>
            <span class="sold">• 100rb+ terjual</span>
          </div>
          <div class="seller">DapurRoti Premium</div>
        </div>
      </article>

      <!-- Tambah lebih banyak kartu untuk demo grid -->
      <article class="product-card">
        <div class="media">
          <span class="badge">70%</span>
          <div class="image" style="background-image:url('https://via.placeholder.com/400x300/D2B48C/000000?text=Donat+Kampung')"></div>
        </div>
        <div class="info">
          <h3 class="title">Donat Kampung Original ...</h3>
          <div class="price">Rp2.250</div>
          <div class="meta">
            <span class="rating">★ 4.7</span>
            <span class="sold">• 10rb+ terjual</span>
          </div>
          <div class="seller">DapurRoti Kota</div>
        </div>
      </article>

      <!-- copy/paste lebih banyak kartu sesuai kebutuhan -->
      <article class="product-card">
        <div class="media">
          <span class="badge">25%</span>
          <div class="image" style="background-image:url('https://via.placeholder.com/400x300/DEB887/000000?text=Sourdough')"></div>
        </div>
        <div class="info">
          <h3 class="title">Sourdough Gandum Asli ...</h3>
          <div class="price">Rp35.000</div>
          <div class="meta">
            <span class="rating">★ 4.8</span>
            <span class="sold">• 500+ terjual</span>
          </div>
          <div class="seller">DapurRoti Artisan</div>
        </div>
      </article>

      <article class="product-card">
        <div class="media">
          <span class="badge">40%</span>
          <div class="image" style="background-image:url('https://via.placeholder.com/400x300/F5DEB3/000000?text=Cinnamon+Rolls')"></div>
        </div>
        <div class="info">
          <h3 class="title">Cinnamon Rolls Istimewa ...</h3>
          <div class="price">Rp45.000</div>
          <div class="meta">
            <span class="rating">★ 4.6</span>
            <span class="sold">• 2rb+ terjual</span>
          </div>
          <div class="seller">DapurRoti Gourmet</div>
        </div>
      </article>

      <article class="product-card">
        <div class="media">
          <span class="badge hot">55%</span>
          <div class="image" style="background-image:url('https://via.placeholder.com/400x300/FFF8DC/000000?text=Bread+Rolls')"></div>
        </div>
        <div class="info">
          <h3 class="title">Bread Rolls Set 12 pcs ...</h3>
          <div class="price">Rp42.000</div>
          <div class="meta">
            <span class="rating">★ 4.9</span>
            <span class="sold">• 1rb+ terjual</span>
          </div>
          <div class="seller">DapurRoti Keluarga</div>
        </div>
      </article>
      
      <!-- tambahan produk -->
      <article class="product-card">
        <div class="media">
          <span class="badge">15%</span>
          <div class="image" style="background-image:url('https://via.placeholder.com/400x300/F0E68C/000000?text=Roti+Tawar')"></div>
        </div>
        <div class="info">
          <h3 class="title">Roti Tawar Gandum ...</h3>
          <div class="price">Rp15.000</div>
          <div class="meta">
            <span class="rating">★ 4.5</span>
            <span class="sold">• 800+ terjual</span>
          </div>
          <div class="seller">DapurRoti Sehat</div>
        </div>
      </article>
      
      <article class="product-card">
        <div class="media">
          <span class="badge hot">60%</span>
          <div class="image" style="background-image:url('https://via.placeholder.com/400x300/CD853F/000000?text=Roti+Jala')"></div>
        </div>
        <div class="info">
          <h3 class="title">Roti Jala Madu Asli ...</h3>
          <div class="price">Rp28.000</div>
          <div class="meta">
            <span class="rating">★ 4.8</span>
            <span class="sold">• 1.5rb+ terjual</span>
          </div>
          <div class="seller">DapurRoti Tradisional</div>
        </div>
      </article>
    </section>
  </main>
</body>
</html>
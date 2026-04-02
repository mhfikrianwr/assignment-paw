<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Upload Image</title>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="style.css"/>
</head>
<body>

  <div class="page">
    <div class="card">

      <div class="card-header">
        <div class="header-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="2"
               stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="18" rx="2"/>
            <circle cx="8.5" cy="8.5" r="1.5"/>
            <polyline points="21 15 16 10 5 21"/>
          </svg>
        </div>
        <div>
          <h1 class="headline">Upload Image</h1>
          <p class="subline">Pilih atau seret gambar untuk diunggah</p>
        </div>
      </div>

      <div class="accepted">
        <span class="accepted-label">Accepted files</span>
        <span class="badge">.JPG</span>
        <span class="badge">.PNG</span>
        <span class="accepted-size">· Maks. 5 MB</span>
      </div>

      <?php if (!empty($_GET['status']) && $_GET['status'] === 'success'): ?>
      <div class="alert alert-success">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2.2"
             stroke-linecap="round" stroke-linejoin="round">
          <polyline points="20 6 9 17 4 12"/>
        </svg>
        <span>File <strong><?= htmlspecialchars($_GET['filename']) ?></strong> berhasil diupload!</span>
      </div>
      <?php endif; ?>

      <?php if (!empty($_GET['status']) && $_GET['status'] === 'error'): ?>
      <div class="alert alert-error">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2.2"
             stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/>
          <line x1="12" y1="8" x2="12" y2="12"/>
          <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span><?= htmlspecialchars($_GET['message']) ?></span>
      </div>
      <?php endif; ?>

      <form action="../backend/upload.php" method="POST" enctype="multipart/form-data">

        <label class="drop-zone" for="fileInput">
          <input
            type="file"
            id="fileInput"
            name="foto"
            accept=".jpg,.jpeg,.png"
          />
          <div class="drop-icon">
            <svg width="44" height="44" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.4"
                 stroke-linecap="round" stroke-linejoin="round">
              <polyline points="16 16 12 12 8 16"/>
              <line x1="12" y1="12" x2="12" y2="21"/>
              <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
            </svg>
          </div>
          <p class="drop-title">Seret gambar ke sini</p>
          <p class="drop-sub">atau klik tombol di bawah</p>
          <div class="btn-browse">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.2"
                 stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
              <polyline points="17 8 12 3 7 8"/>
              <line x1="12" y1="3" x2="12" y2="15"/>
            </svg>
            Pilih File
          </div>
        </label>

        <div class="preview-section" >
          <div class="preview-header">
            <span class="preview-label">Preview</span>
          </div>
          <div class="preview-placeholder" id="previewBox">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="1.5"
                 stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="3" width="18" height="18" rx="2"/>
              <circle cx="8.5" cy="8.5" r="1.5"/>
              <polyline points="21 15 16 10 5 21"/>
            </svg>
            <p>Preview gambar akan tampil di sini<br/>setelah file dipilih</p>
          </div>
        </div>

        <button class="btn-upload" type="submit">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
               stroke="currentColor" stroke-width="2.2"
               stroke-linecap="round" stroke-linejoin="round">
            <polyline points="16 16 12 12 8 16"/>
            <line x1="12" y1="12" x2="12" y2="21"/>
            <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
          </svg>
          Upload Sekarang
        </button>

      </form>

    </div>
  </div>

  <script>
  const fileInput = document.getElementById('fileInput');
  const previewBox = document.getElementById('previewBox');

  fileInput.addEventListener('change', function () {
    const file = this.files[0];

    if (!file) {
      previewBox.innerHTML = `
        <p>Preview gambar akan tampil di sini<br/>setelah file dipilih</p>
      `;
      return;
    }

    // Validasi tipe file (opsional, biar aman)
    if (!file.type.startsWith('image/')) {
      previewBox.innerHTML = `<p style="color:red;">File bukan gambar!</p>`;
      return;
    }

    const reader = new FileReader();

    reader.onload = function (e) {
      previewBox.innerHTML = `
        <img src="${e.target.result}" 
             alt="Preview" 
             style="max-width:100%; border-radius:12px;">
      `;
    };

    reader.readAsDataURL(file);
  });
</script>
</body>
</html>
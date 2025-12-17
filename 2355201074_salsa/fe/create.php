<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Form Mobil</title>
    <script src="jquery.js"></script>

    <style>
      body {
        font-family: Arial, sans-serif;
        background: #f4f6f8;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
      }

      form {
        background: #ffffff;
        padding: 25px;
        width: 350px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      }

      h2 {
        text-align: center;
        margin-bottom: 20px;
      }

      .form-group {
        margin-bottom: 15px;
      }

      label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
      }

      input,
      select {
        width: 100%;
        padding: 8px;
        border-radius: 5px;
        border: 1px solid #ccc;
      }

      input:focus,
      select:focus {
        outline: none;
        border-color: #007bff;
      }

      .error {
        font-size: 12px;
        color: red;
      }

      button {
        width: 100%;
        padding: 10px;
        background: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
      }

      button:hover {
        background: #0056b3;
      }
    </style>
  </head>

  <body>
    <form id="myForm" enctype="multipart/form-data">
      <h2>Form Data Mobil</h2>

      <div class="form-group">
        <label>Brand</label>
        <input type="text" name="brand" id="brand" />
        <span class="error brand"></span>
      </div>

      <div class="form-group">
        <label>Model</label>
        <input type="text" name="model" id="model" />
        <span class="error model"></span>
      </div>

      <div class="form-group">
        <label>Year</label>
        <input type="number" name="year" id="year" />
        <span class="error year"></span>
      </div>

      <div class="form-group">
        <label>Price</label>
        <input type="number" name="price" id="price" />
        <span class="error price"></span>
      </div>

      <div class="form-group">
        <label>Transmission</label>
        <select name="transmission" id="transmission">
          <option value="">-- Pilih --</option>
          <option value="manual">Manual</option>
          <option value="automatic">Automatic</option>
        </select>
        <span class="error transmission"></span>
      </div>

      <div class="form-group">
        <label>Photo (Optional)</label>
        <input type="file" name="photo" id="photo" />
        <span class="error photo"></span>
      </div>

      <button type="submit">Simpan</button>
    </form>

    <script>
      $(document).on("submit", "#myForm", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
          url: "http://localhost/25pbp_uts_23pagib/2355201074_salsa/create.php",
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,

          beforeSend: function () {
            $(".error").text("");
          },

          success: function (res) {
            alert(res.msg + ", Data mobil berhasil ditambahkan");
            $("#myForm")[0].reset();
          },

          error: function (res) {
            let response = res.responseJSON;

            Object.entries(response.errors).forEach(([key, val]) => {
              $("." + key).text(val);
            });
          },
        });
      });
    </script>
  </body>
</html>

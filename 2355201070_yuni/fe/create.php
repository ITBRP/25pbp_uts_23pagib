<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Form Mobil</title>
    <script src="jquery.js"></script>

    <!-- CSS Tampilan (warna hijau–tosca) -->
    <style>
      body {
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #e0f7fa, #e8f5e9);
        display: flex;
        justify-content: center;
        padding-top: 40px;
      }

      form {
        background: #ffffff;
        padding: 25px 30px;
        width: 400px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
      }

      form div {
        margin-bottom: 15px;
      }

      label {
        font-weight: 600;
        color: #2e7d32;
      }

      input,
      select {
        width: 100%;
        padding: 9px 10px;
        margin-top: 5px;
        border: 1px solid #a5d6a7;
        border-radius: 6px;
        font-size: 14px;
      }

      input:focus,
      select:focus {
        outline: none;
        border-color: #26a69a;
        box-shadow: 0 0 0 2px rgba(38, 166, 154, 0.2);
      }

      button {
        width: 100%;
        padding: 11px;
        background: #26a69a;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 15px;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
      }

      button:hover {
        background: #1e8e85;
      }

      .error {
        font-size: 12px;
        margin-top: 4px;
        display: block;
      }
    </style>
  </head>

  <body>
    <form id="myForm" enctype="multipart/form-data">
      <div>
        <label>Brand</label><br />
        <input type="text" name="brand" id="brand" />
        <span class="error brand" style="color: red"></span>
      </div>

      <div>
        <label>Model</label><br />
        <input type="text" name="model" id="model" />
        <span class="error model" style="color: red"></span>
      </div>

      <div>
        <label>Year</label><br />
        <input type="number" name="year" id="year" />
        <span class="error year" style="color: red"></span>
      </div>

      <div>
        <label>Price</label><br />
        <input type="number" name="price" id="price" />
        <span class="error price" style="color: red"></span>
      </div>

      <div>
        <label>Transmission</label><br />
        <select name="transmission" id="transmission">
          <option value="">-- Pilih --</option>
          <option value="manual">Manual</option>
          <option value="automatic">Automatic</option>
        </select>
        <span class="error transmission" style="color: red"></span>
      </div>

      <div>
        <label>Photo (Optional)</label><br />
        <input type="file" name="photo" id="photo" />
        <span class="error photo" style="color: red"></span>
      </div>

      <div>
        <button type="submit">Simpan</button>
      </div>
    </form>

    <script>
      $(document).on("submit", "#myForm", function (e) {
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
          url: "http://localhost/25pbp_uts_23pagib/2355201070_yuni/create.php",
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

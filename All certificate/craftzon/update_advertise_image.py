import re

with open('seller/seller_advertise.php', 'r', encoding='utf-8') as f:
    text = f.read()

# 1. Add data-image and hidden existing_image input
text = text.replace(
    '''data-pid="<?= $p['product_id'] ?>">''',
    '''data-pid="<?= $p['product_id'] ?>"
                        data-image="<?= htmlspecialchars($p['image']) ?>">'''
)

text = text.replace(
    '''<input type="hidden" name="action" value="add_ad">''',
    '''<input type="hidden" name="action" value="add_ad">
        <input type="hidden" name="existing_image" id="existing_image" value="">'''
)

# 2. Add image display tag
text = text.replace(
    '''<label>Upload Image</label>''',
    '''<label>Product Image</label>
        <div id="image_preview_container" style="display:none; margin-bottom: 15px;">
            <img id="image_preview" src="" alt="Product Image" style="max-width: 200px; border-radius: 8px; border: 2px solid #ccc;">
            <p style="font-size: 12px; color: gray;">You can upload a new image below to override this.</p>
        </div>
        <label>Upload New Image (Optional)</label>'''
)

# 3. Remove required from file input
text = text.replace(
    '''<input type="file" name="image" class="form-control" accept="image/*" required>''',
    '''<input type="file" name="image" class="form-control" accept="image/*">'''
)

# 4. JS logic to fetch image
js_old = '''$("#pid").val(selected.data('pid') || '');
    });'''
js_new = '''$("#pid").val(selected.data('pid') || '');
        var imgPath = selected.data('image') || '';
        if(imgPath) {
            $("#image_preview").attr("src", "../" + imgPath);
            $("#image_preview_container").show();
            $("#existing_image").val(imgPath);
        } else {
            $("#image_preview_container").hide();
            $("#existing_image").val("");
        }
    });'''
text = text.replace(js_old, js_new)

# 5. PHP logic to use existing image
php_old = '''$image_path = NULL;
if (!empty($_FILES['image']['name'])) {
    $image_name = time() . '_' . basename($_FILES['image']['name']);
    $target_dir = "../advrtisephoto/";
    $target_path = $target_dir . $image_name;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
        $image_path = "advrtisephoto/" . $image_name; // o. save full relative path
    }
}'''

php_new = '''$image_path = isset($_POST['existing_image']) ? $_POST['existing_image'] : NULL;
if (!empty($_FILES['image']['name'])) {
    $image_name = time() . '_' . basename($_FILES['image']['name']);
    $target_dir = "../advrtisephoto/";
    $target_path = $target_dir . $image_name;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
        $image_path = "advrtisephoto/" . $image_name; // override existing image
    }
}'''
text = text.replace(php_old, php_new)

with open('seller/seller_advertise.php', 'w', encoding='utf-8') as f:
    f.write(text)

print("Updated seller_advertise.php with image fetching logic")

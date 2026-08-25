with open('seller/addproduct.php', 'r') as f:
    text = f.read()

bad_str = '''            } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Image upload failed!'
                });
            </script>";
        }
    }

    // Insert into product_table safely using prepared statements'''
good_str = '''            } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Image upload failed!'
                });
            </script>";
            }
        } else {
            // Invalid extension
            echo "<script>Swal.fire({icon:'error',title:'Invalid file',text:'Only valid images allowed!'});</script>";
        }
    }

    // Insert into product_table safely using prepared statements'''

if bad_str in text:
    text = text.replace(bad_str, good_str)
    with open('seller/addproduct.php', 'w') as f:
        f.write(text)
    print("Fixed addproduct.php")
else:
    print("Not found")


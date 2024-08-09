function showProductItems() {
    $.ajax({
        url: "./adminView/viewAllProducts.php",
        method: "post",
        data: { record: 1 },
        success: function(data) {
            $('.allContent-section').html(data);
        }
    });
}

function showDashboard() {
    $.ajax({
        url: "./adminView/data.php",
        method: "post",
        data: { record: 1 },
        success: function(data) {
            $('.allContent-section').html(data);
        }
    });
}

function showCategory() {
    $.ajax({
        url: "./adminView/viewCategories.php",
        method: "post",
        data: { record: 1 },
        success: function(data) {
            $('.allContent-section').html(data);
        }
    });
}

function showSizes() {
    $.ajax({
        url: "./adminView/viewSizes.php",
        method: "post",
        data: { record: 1 },
        success: function(data) {
            $('.allContent-section').html(data);
        }
    });
}

function showProductSizes() {
    $.ajax({
        url: "./adminView/viewProductSizes.php",
        method: "post",
        data: { record: 1 },
        success: function(data) {
            $('.allContent-section').html(data);
        }
    });
}

function showCustomers() {
    $.ajax({
        url: "./adminView/viewCustomers.php",
        method: "post",
        data: { record: 1 },
        success: function(data) {
            $('.allContent-section').html(data);
        }
    });
}

function showEventReport() {
    $.ajax({
        url: "./reports/index.php",
        method: "post",
        data: { record: 1 },
        success: function(data) {
            $('.allContent-section').html(data);
        }
    });
}

function showOrders() {
    $.ajax({
        url: "./adminView/viewAllOrders.php",
        method: "post",
        data: { record: 1 },
        success: function(data) {
            $('.allContent-section').html(data);
        }
    });
}

function ChangeOrderStatus(id) {
    $.ajax({
        url: "./controller/updateOrderStatus.php",
        method: "post",
        data: { record: id },
        success: function(data) {
            alert('Order Status updated successfully');
            $('form').trigger('reset');
            showOrders();
        }
    });
}

function ChangePay(id) {
    $.ajax({
        url: "./controller/updatePayStatus.php",
        method: "post",
        data: { record: id },
        success: function(data) {
            alert('Payment Status updated successfully');
            $('form').trigger('reset');
            showOrders();
        }
    });
}

// add product data with Blob support for product image
function addItems() {
    var p_name = $('#p_name').val();
    var p_desc = $('#p_desc').val();
    var p_price = $('#p_price').val();
    var category = $('#category').val();
    var upload = $('#upload').val();
    var file = $('#file')[0].files[0];

    // Convert file to blob
    var reader = new FileReader();
    reader.onloadend = function() {
        var blob = new Blob([reader.result], { type: file.type });

        var fd = new FormData();
        fd.append('p_name', p_name);
        fd.append('p_desc', p_desc);
        fd.append('p_price', p_price);
        fd.append('category', category);
        fd.append('file', blob); // Append the blob here
        fd.append('upload', upload);

        $.ajax({
            url: "./controller/addItemController.php",
            method: "post",
            data: fd,
            processData: false,
            contentType: false,
            success: function(data) {
                alert('Product Added successfully.');
                $('form').trigger('reset');
                showProductItems();
            }
        });
    };

    if (file) {
        reader.readAsArrayBuffer(file);
    }
}

// update product after submit with Blob support for product image
function updateItems() {
    var product_id = $('#product_id').val();
    var p_name = $('#p_name').val();
    var p_desc = $('#p_desc').val();
    var p_price = $('#p_price').val();
    var category = $('#category').val();
    var existingImage = $('#existingImage').val();
    var newImage = $('#newImage')[0].files[0];

    var fd = new FormData();
    fd.append('product_id', product_id);
    fd.append('p_name', p_name);
    fd.append('p_desc', p_desc);
    fd.append('p_price', p_price);
    fd.append('category', category);
    fd.append('existingImage', existingImage);

    if (newImage) {
        // Convert new image to blob
        var reader = new FileReader();
        reader.onloadend = function() {
            var blob = new Blob([reader.result], { type: newImage.type });
            fd.append('newImage', blob); // Append the blob here

            $.ajax({
                url: './controller/updateItemController.php',
                method: 'post',
                data: fd,
                processData: false,
                contentType: false,
                success: function(data) {
                    alert('Data Update Success.');
                    $('form').trigger('reset');
                    showProductItems();
                }
            });
        };

        reader.readAsArrayBuffer(newImage);
    } else {
        // If no new image, proceed with the existing form data
        $.ajax({
            url: './controller/updateItemController.php',
            method: 'post',
            data: fd,
            processData: false,
            contentType: false,
            success: function(data) {
                alert('Data Update Success.');
                $('form').trigger('reset');
                showProductItems();
            }
        });
    }
}

// delete product data
function itemDelete(id) {
    $.ajax({
        url: "./controller/deleteItemController.php",
        method: "post",
        data: { record: id },
        success: function(data) {
            alert('Items Successfully deleted');
            $('form').trigger('reset');
            showProductItems();
        }
    });
}

// delete cart data
function cartDelete(id) {
    $.ajax({
        url: "./controller/deleteCartController.php",
        method: "post",
        data: { record: id },
        success: function(data) {
            alert('Cart Item Successfully deleted');
            $('form').trigger('reset');
            showMyCart();
        }
    });
}

function itemEditForm(id) {
    $.ajax({
        url: "./adminView/editItemForm.php",
        method: "post",
        data: { record: id },
        success: function(data) {
            $('.allContent-section').html(data);
        }
    });
}

function eachDetailsForm(id) {
    $.ajax({
        url: "./view/viewEachDetails.php",
        method: "post",
        data: { record: id },
        success: function(data) {
            $('.allContent-section').html(data);
        }
    });
}

// delete category data
function categoryDelete(id) {
    $.ajax({
        url: "./controller/catDeleteController.php",
        method: "post",
        data: { record: id },
        success: function(data) {
            alert('Category Successfully deleted');
            $('form').trigger('reset');
            showCategory();
        }
    });
}

// delete size data
function sizeDelete(id) {
    $.ajax({
        url: "./controller/deleteSizeController.php",
        method: "post",
        data: { record: id },
        success: function(data) {
            alert('Size Successfully deleted');
            $('form').trigger('reset');
            showSizes();
        }
    });
}

// delete variation data
function variationDelete(id) {
    $.ajax({
        url: "./controller/deleteVariationController.php",
        method: "post",
        data: { record: id },
        success: function(data) {
            alert('Successfully deleted');
            $('form').trigger('reset');
            showProductSizes();
        }
    });
}

// edit variation data
function variationEditForm(id) {
    $.ajax({
        url: "./adminView/editVariationForm.php",
        method: "post",
        data: { record: id },
        success: function(data) {
            $('.allContent-section').html(data);
        }
    });
}

// update variation after submit
function updateVariations() {
    var v_id = $('#v_id').val();
    var product = $('#product').val();
    var size = $('#size').val();
    var qty = $('#qty').val();
    var fd = new FormData();
    fd.append('v_id', v_id);
    fd.append('product', product);
    fd.append('size', size);
    fd.append('qty', qty);

    $.ajax({
        url: './controller/updateVariationController.php',
        method: 'post',
        data: fd,
        processData: false,
        contentType: false,
        success: function(data) {
            alert('Update Success.');
            $('form').trigger('reset');
            showProductSizes();
        }
    });
}

function search(id) {
    $.ajax({
        url: "./controller/searchController.php",
        method: "post",
        data: { record: id },
        success: function(data) {
            $('.eachCategoryProducts').html(data);
        }
    });
}

function quantityPlus(id) {
    $.ajax({
        url: "./controller/addQuantityController.php",
        method: "post",
        data: { record: id },
        success: function(data) {
            showMyCart();
        }
    });
}

function quantityMinus(id) {
    $.ajax({
        url: "./controller/minusQuantityController.php",
        method: "post",
        data: { record: id },
        success: function(data) {
            showMyCart();
        }
    });
}
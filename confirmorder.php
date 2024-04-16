<?php
$products = $_SESSION['cart']['products'];
?>

<?=template_header('Place Order')?>

<div class="cart content-wrapper">
    <h1>Confirm your order:</h1>
    <p>Before proceeding to payment, here are your order details:</p>
    <form action="" method="post" id="orderForm">
        <table>
            <thead>
                <tr>
                    <td colspan="2">Product</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Total</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td class="img">
                        <a href="index.php?page=product&id=<?=$product['id']?>">
                            <img src="imgs/<?=$product['img']?>" width="50" height="50" alt="<?=$product['name']?>">
                        </a>
                    </td>
                    <td class="product-name">
                        <a href="index.php?page=product&id=<?=$product['id']?>"><?=$product['name']?></a>
                    </td>
                    <td class="price">&dollar;<?=$product['price']?></td>
                    <td class="quantity"><?= $_SESSION['cart'][$product['id']]?></td>
                    <td class="price">&dollar;<?=$product['price'] * $_SESSION['cart'][$product['id']]?></td>
                </tr>
                <?php endforeach; ?>
               
            </tbody>
        </table>
        <div class="subtotal">
            <span class="text">Subtotal</span>
            <span class="price">&dollar;<?=$_SESSION['cart']['subtotal']?></span>
        </div>
        <div class="buttons">
            <input type="submit" value="Back To Cart" onclick="setFormAction('cart')" name="backtocart">
            <input type="submit" value="Checkout" onclick="setFormAction('getinfo')" name="getinfo">
        </div>
    </form>
    <script>
    function setFormAction(page) {
        document.getElementById('orderForm').action = 'index.php?page=' + page;
    }
    </script>
</div>

<?=template_footer()?>
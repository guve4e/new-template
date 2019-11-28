<?php
include (HTTP_PATH . "/jshttp.php");
?>

<script>

    function addToCart(sku, userId)
    {
        try {
            let qty = parseInt(document.getElementById("qtyTextField").value);

            if (isNaN(qty))
                throw new Error("Not a numerica value! \n");

            if ((qty < 1) || (qty > 100))
                throw new Error("Quantity is not in range! \n");

            let queryString = `user/${userId}/addToShoppingCart?sku=${sku}&qty=${qty}`;

            const http = new JSHttp()
                .setApi("shoppingCart")
                .setMethod("PUT")
                .setService("shoppingCart")
                .setParameter(queryString)
                .send();

            getNewCart(userId);
        } catch (e) {
            alert(e + "Please enter only numeric values between 1 and 100")
        }
    }

    function getNewCart(userId)
    {
        let queryString = `byUserId/${userId}`;

        new JSHttp()
            .setApi("shoppingCart")
            .setMethod("GET")
            .setService("shoppingCart")
            .setParameter(queryString)
            .setOutputElement(["product-count"])
            .setOutputElementReceivingJsonKey(['numberOfProducts'])
            .setRefresh(true)
            .setAsync(true)
            .send();
    }

    function incrementQty()
    {
        let qty = parseInt(document.getElementById("qtyTextField").value);

        qty++;

        document.getElementById("qtyTextField").value = qty;
    }

    function decrementQty()
    {
        let qty = parseInt(document.getElementById("qtyTextField").value);

        qty--;

        if (qty < 1) qty = 1;

        document.getElementById("qtyTextField").value = qty;
    }

</script>



<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-148508973-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-148508973-1');
</script>
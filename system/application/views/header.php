<body<?php if (isset($color)) { echo " class=\"$color\""; } ?>>
<div id="main_container">
<div id="header">
    <div id="header_inner">
        <a id="logo" href="/"></a>
        <div id="title">National Institution for Trial Advocacy</div>
        <div id="search_bar">
            <div id="search_bar_inner">
                <div id="search_bar_search">
                    <input name="search" type="text" />
                </div>
                <!-- <a id="search_bar_rss" class="search_bar_rss has_hover" href=""></a> -->
                <a id="search_bar_cart" class="search_bar_cart has_hover" href="/shop/cart"></a>
                <a id="search_bar_account" class="search_bar_account has_hover" href="<?php echo $accountLink ; ?>"></a>
                <a id="search_bar_print" class="search_bar_print has_hover" href=""></a>
            </div>
        </div>
    </div> <!-- #header_inner -->
</div> <!-- #header -->

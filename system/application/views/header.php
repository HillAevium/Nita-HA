<body<?php if (isset($color)) { echo " class=\"$color\""; } ?>>
<div id="main_container">

<div id="header">
    <div id="search_bar">
        <div id="search_bar_search">
            <input name="search" type="text" />
        </div>
        <a id="search_bar_cart" class="search_bar_cart has_hover" href="/MyCart"></a>
        <img class="tooltip" src="/resources/images/tooltip_cart.png" />
        <a id="search_bar_account" class="search_bar_account has_hover" href="<?php echo $accountLink ; ?>"></a>
        <img class="tooltip" src="/resources/images/tooltip_my_account.png" />
        <a id="search_bar_print" class="search_bar_print has_hover" href="#" onclick="window.print();return false;"></a>
        <img class="tooltip" src="/resources/images/tooltip_print.png" />
    </div>
    <div id="header_inner">
        <a id="logo" href="/"></a>
        <div id="title">National Institution for Trial Advocacy</div>
    </div> <!-- #header_inner -->
</div> <!-- #header -->

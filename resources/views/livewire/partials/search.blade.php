<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<!-- Search Popup -->
<div class="search-popup">
    <div class="color-layer"></div>
    <button class="close-search"><span class="flaticon-close"></span></button>
    <form method="post" action="blog.html">
        <div class="form-group">
            <input type="search" name="search-field" value="" placeholder="Search Here" required="">
            <button class="fa fa-solid fa-magnifying-glass fa-fw" type="submit"></button>
        </div>
    </form>
</div>
<!-- End Search Popup -->

<!-- Banner Tabs -->
<div class="banner-tabs">
    <!-- Product Tabs -->
    <div class="prod-tabs tabs-box">

        <!-- Tab Btns -->
        <ul class="tab-btns tab-buttons clearfix">
            <li data-tab="#prod-buy" class="tab-btn active-btn">buy</li>
            <li data-tab="#prod-rent" class="tab-btn">rent</li>
        </ul>
        
        <!-- Tabs Container -->
        <div class="tabs-content">
            
            <!-- Tab -->
            <div class="tab active-tab" id="prod-buy">
                <div class="content">
                    <!-- Default Form -->
                    <div class="default-form">
                        <form method="post" wire:submit.prevent="buy">
                            <div class="row clearfix">
                                
                                <div class="col-12 form-group">
                                    <label>Location</label>
                                    <select name="country" class="custom-select-box">
                                        <option>Florida, Usa</option>
                                        <option>Berlin</option>
                                        <option>France</option>
                                        <option>Pakistan</option>
                                    </select>
                                </div>
                                
                            </div>
                            <!-- Button Box -->
                            <div class="button-box">
                                <button class="submit-btn">Search Now <i class="flaticon-search-interface-symbol"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Tab -->
            <div class="tab" id="prod-rent">
                <div class="content">
                    <!-- Default Form -->
                    <div class="default-form">
                        <form method="post" action="contact.html">
                            <div class="row clearfix">
                                
                                <div class="col-lg-12 form-group">
                                    <label>Location</label>
                                    <select name="country" class="custom-select-box">
                                        <option>Florida, Usa</option>
                                        <option>Berlin</option>
                                        <option>France</option>
                                        <option>Pakistan</option>
                                    </select>
                                </div>
                                
                            </div>
                            <!-- Button Box -->
                            <div class="button-box">
                                <button class="submit-btn">Search Now <i class="flaticon-search-interface-symbol"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
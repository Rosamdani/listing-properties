<div class="px-3 w-100 d-flex gap-3">
    <div class="dropdown"> 
        <button type="button" 
                class="btn btn-lg btn-white rounded-pill dropdown-toggle"
                data-bs-toggle="dropdown"> 
            {{ __('Harga') }}<span class="caret"></span> 
        </button> 
        <div class="dropdown-menu" style="width: 300px;"> 
            <form class="m-3"> 
                <div class="form-group"> 
                    <label for="fm1" class="form-label"> 
                        {{ __('Min Harga') }} 
                    </label> 
                    <input type="number" class="form-control" 
                            id="fm1" 
                            placeholder="Enter min price" 
                            required> 
                </div> 
                <div class="form-group"> 
                    <label for="fm2" class="form-label"> 
                        {{ __('Max Harga') }} 
                    </label> 
                    <input type="number" class="form-control" 
                            id="fm2" placeholder="Enter max price"> 
                </div> 
            </form> 
        </div> 
    </div> 
    <div class="dropdown"> 
        <button type="button" 
                class="btn btn-lg btn-white rounded-pill dropdown-toggle"
                data-bs-toggle="dropdown"> 
            {{ __('Jumlah Kamar') }}<span class="caret"></span> 
        </button> 
        <div class="dropdown-menu px-1" style="width: fit-content;"> 
            <form class="m-3"> 
                <div class="btn-group d-flex" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                    <label class="btn btn-white" for="btnradio1">1</label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                    <label class="btn btn-white" for="btnradio2">2</label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
                    <label class="btn btn-white" for="btnradio3">3</label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off">
                    <label class="btn btn-white" for="btnradio4">4</label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio5" autocomplete="off">
                    <label class="btn btn-white" for="btnradio5">5+</label>
                </div>
            </form> 
        </div> 
    </div> 
    <div class="dropdown"> 
        <button type="button" 
                class="btn btn-lg btn-white rounded-pill dropdown-toggle"
                data-bs-toggle="dropdown"> 
            {{ __('Tipe Properti') }}<span class="caret"></span> 
        </button> 
        <div class="dropdown-menu px-1" style="width: fit-content;"> 
            <form class="m-3"> 
                <div class="btn-group d-flex" role="group" aria-label="Property type toggle button group">
                    <input type="radio" class="btn-check" name="propertyType" id="propertyType1" autocomplete="off" checked>
                    <label class="btn btn-white d-flex align-items-center" for="propertyType1">
                        <i class="flaticon-home mr-2"></i> {{ __('Rumah') }}
                    </label>

                    <input type="radio" class="btn-check" name="propertyType" id="propertyType2" autocomplete="off">
                    <label class="btn btn-white d-flex align-items-center" for="propertyType2">
                        <i class="flaticon-building mr-2"></i> {{ __('Apartemen') }}
                    </label>

                    <input type="radio" class="btn-check" name="propertyType" id="propertyType3" autocomplete="off">
                    <label class="btn btn-white d-flex align-items-center" for="propertyType3">
                        <i class="flaticon-store mr-2"></i> {{ __('Ruko') }}
                    </label>

                    <input type="radio" class="btn-check" name="propertyType" id="propertyType4" autocomplete="off">
                    <label class="btn btn-white d-flex align-items-center" for="propertyType4">
                        <i class="flaticon-land mr-2"></i> {{ __('Tanah') }}
                    </label>

                    <input type="radio" class="btn-check" name="propertyType" id="propertyType5" autocomplete="off">
                    <label class="btn btn-white d-flex align-items-center" for="propertyType5">
                        <i class="flaticon-office mr-2"></i> {{ __('Kantor') }}
                    </label>
                </div>
            </form> 
        </div> 
    </div> 
    <button class="btn btn-white rounded-pill" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">{{ __('Semua Filter') }} </button>

        <div class="offcanvas offcanvas-end" style="width: 700px;" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasRightLabel">Offcanvas right</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            ...
        </div>
    </div>

    <button class="btn btn-primary rounded-pill px-4" type="button"> {{ __('Simpan Pencarian') }} </button>
</div>

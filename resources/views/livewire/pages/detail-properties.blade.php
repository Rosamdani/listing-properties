<?php

use Livewire\Volt\Component;
use App\Models\Properties;
use Livewire\Attributes\Layout;

new #[Layout('components.layouts.app')] class extends Component {
    public Properties $property;

    public function mount($slug)
    {
        $this->property = Properties::where('slug', $slug)->firstOrFail();
    }
}; ?>

<div class="sidebar-page-container">
    <div class="auto-container">
        <div class="row clearfix">
            <!-- Content Side -->
            <div class="content-side col-lg-8 col-md-12 col-sm-12">
                <!-- Property Detail -->
                <div class="property-detail">
                    <div class="property-detail_inner">
                        <livewire:pages.detail-properties.property-images :property="$property" />

                        <div class="property-detail_content">
                            <livewire:pages.detail-properties.property-header :property="$property" />
                            <livewire:pages.detail-properties.property-description :property="$property" />
                            <livewire:pages.detail-properties.property-details :property="$property" />
                            <livewire:pages.detail-properties.property-features :property="$property" />
                            <livewire:pages.detail-properties.property-amenities :property="$property" />
                            <livewire:pages.detail-properties.property-location :property="$property" />
                            <livewire:pages.detail-properties.property-contact-form />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Side -->
            <div class="sidebar-side col-lg-4 col-md-12 col-sm-12">
                <aside class="sidebar">
                    <div class="sidebar-inner">
                        <livewire:pages.detail-properties.property-sidebar-agent :property="$property" />
                        <livewire:pages.detail-properties.property-sidebar-contact />
                    </div>
                </aside>
            </div>
        </div>
    </div>
</div>

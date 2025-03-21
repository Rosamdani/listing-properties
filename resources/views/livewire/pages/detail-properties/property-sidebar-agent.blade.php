<?php

use Livewire\Volt\Component;
use App\Models\Properties;

new class extends Component {
    public Properties $property;
}; ?>

<div>
    <div class="sidebar-widget about-widget">
        <div class="widget-content">
            <div class="author-image">
                <img src="{{ asset('assets/images/resource/agent-default.png') }}" alt="{{ $property->contact_name }}" />
            </div>
            <h5>{{ $property->contact_name }}</h5>
            <div class="designation">Property Agent</div>
            <div class="text">I'm here to help you find your dream property. Feel free to contact me for any questions
                about this property.</div>

            <!-- Contact Info Box -->
            <div class="contact-info-box mt-3">
                <div class="contact-info-item">
                    <i class="fa-solid fa-phone"></i>
                    <a href="tel:{{ $property->contact_phone }}">{{ $property->contact_phone }}</a>
                </div>
                <div class="contact-info-item">
                    <i class="fa-solid fa-envelope"></i>
                    <a href="mailto:{{ $property->contact_email }}">{{ $property->contact_email }}</a>
                </div>
            </div>

            <!-- Social Box -->
            <div class="social-box mt-3">
                <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                <a href="#"><i class="fa-brands fa-linkedin"></i></a>
            </div>
        </div>
    </div>
</div>

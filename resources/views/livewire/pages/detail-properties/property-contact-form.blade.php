<?php

use Livewire\Volt\Component;
use App\Mail\PropertyInquiry;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Rule;

new class extends Component {
    #[Rule('required|min:3|max:100')]
    public $name = '';

    #[Rule('required|email|max:100')]
    public $email = '';

    #[Rule('required|min:10|max:1000')]
    public $message = '';

    public $success = false;

    public function sendMessage()
    {
        $validated = $this->validate();

        // Send email
        Mail::to(config('mail.property_inquiries_email', 'info@example.com'))->send(new PropertyInquiry($validated));

        $this->reset(['name', 'email', 'message']);
        $this->success = true;
    }
}; ?>

<div>
    <div class="comment-form_outer mt-5">
        <div class="group-title">
            <h3>SEND AN INQUIRY</h3>
        </div>

        @if ($success)
            <div class="alert alert-success">
                Your message has been sent successfully. We'll get back to you soon!
            </div>
        @endif

        <!-- Comment Form -->
        <div class="comment-form">
            <form wire:submit="sendMessage">
                <div class="row clearfix">
                    <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                        <i class="flaticon-user"></i>
                        <input type="text" wire:model="name" placeholder="Full Name"
                            class="@error('name') error-field @enderror">
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 form-group">
                        <i class="flaticon-mail"></i>
                        <input type="email" wire:model="email" placeholder="Email Address"
                            class="@error('email') error-field @enderror">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                        <textarea wire:model="message" placeholder="Type your message here..." class="@error('message') error-field @enderror"></textarea>
                        @error('message')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 form-group">
                        <!-- Button Box -->
                        <button type="submit" class="theme-btn btn-style-one">
                            <span class="btn-wrap">
                                <span class="text-one">Send Inquiry</span>
                                <span class="text-two">Send Inquiry</span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- End Comment Form -->
    </div>
</div>

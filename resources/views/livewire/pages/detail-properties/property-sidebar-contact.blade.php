<?php

use Livewire\Volt\Component;
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
        $this->validate();

        // Process the form submission
        // You would typically send an email or save to database here

        $this->reset(['name', 'email', 'message']);
        $this->success = true;
    }
}; ?>

<div>
    <div class="sidebar-widget message-widget">
        <div class="widget-content">
            <h5 class="sidebar-widget_title">Quick Inquiry</h5>

            @if ($success)
                <div class="alert alert-success">
                    Your message has been sent successfully!
                </div>
            @endif

            <div class="message-form">
                <form wire:submit="sendMessage">
                    <!-- Form Group -->
                    <div class="form-group">
                        <input type="text" wire:model="name" placeholder="Name*"
                            class="@error('name') error-field @enderror">
                        @error('name')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <!--Form Group-->
                    <div class="form-group">
                        <input type="email" wire:model="email" placeholder="Email*"
                            class="@error('email') error-field @enderror">
                        @error('email')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <textarea wire:model="message" placeholder="Message" class="@error('message') error-field @enderror"></textarea>
                        @error('message')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="theme-btn btn-style-one">
                            <span class="btn-wrap">
                                <span class="text-one">Submit Now</span>
                                <span class="text-two">Submit Now</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

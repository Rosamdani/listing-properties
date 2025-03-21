<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <flux:breadcrumbs class="mb-2">
        <flux:breadcrumbs.item href="{{ route('dashboard') }}" wire:navigate>Dashboard</flux:breadcrumbs.item>
        <flux:breadcrumbs.item href="{{ route('properties.index') }}" wire:navigate>Properties</flux:breadcrumbs.item>
    </flux:breadcrumbs>
    <flux:heading size="xl" level="1" class="mb-6">Properties</flux:heading>

    <flux:separator variant="subtle" class="mb-4" />

    <div class="flex justify-between items-center mb-4 space-x-2">
        <flux:input type="text" placeholder="Search properties..." class="w-20" />
        <flux:select placeholder="Filter by type" class="w-20">
            <flux:select.option value="all">All</flux:select.option>
            <flux:select.option value="house">House</flux:select.option>
            <flux:select.option value="apartment">Apartment</flux:select.option>
            <flux:select.option value="condo">Condo</flux:select.option>
        </flux:select>
        <flux:button class="ml-2" icon="plus" href="">Add Property</flux:button>
    </div>

    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                <th scope="col" class="relative px-6 py-3">
                    <span class="sr-only">Edit</span>
                </th>
            </tr>
        </thead>
        <tbody class="bg-gray-800">
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">Property 1</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">House</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">$500,000</td>
                <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">For Sale</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-500">Edit</a>
                </td>
            </tr>
            <!-- More rows... -->
        </tbody>
    </table>
</div>
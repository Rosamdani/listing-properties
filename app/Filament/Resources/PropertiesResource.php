<?php

namespace App\Filament\Resources;

use App\Enum\Property\Status;
use App\Enum\PropertyStatus;
use App\Enum\PropertyType;
use App\Filament\Resources\PropertiesResource\Pages;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Models\Properties;
use ArberMustafa\FilamentLocationPickrField\Forms\Components\LocationPickr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use RalphJSmit\Filament\SEO\SEO;
use Tapp\FilamentGoogleAutocomplete\Forms\Components\GoogleAutocomplete;

class PropertiesResource extends Resource
{
    protected static ?string $model = Properties::class;

    protected static ?string $navigationIcon = 'heroicon-s-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('PropertiesTabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Informasi Dasar')
                            ->label(trans('filament.properties.form.basic_information'))
                            ->icon('heroicon-s-home')
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label(trans('filament.properties.form.title'))
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('property_type')
                                    ->options([
                                        'house' => 'Rumah',
                                        'apartment' => 'Apartemen',
                                        'land' => 'Tanah',
                                        'commercial' => 'Komersial',
                                        'villa' => 'Villa',
                                    ])
                                    ->required()
                                    ->label(trans('filament.properties.form.property_type')),
                                Forms\Components\Select::make('listing_type')
                                    ->options([
                                        'sale' => 'Dijual',
                                        'rent' => 'Disewa',
                                    ])
                                    ->required()
                                    ->label(trans('filament.properties.form.listing_type')),
                                Forms\Components\TextInput::make('price')
                                    ->required()
                                    ->numeric()
                                    ->label('Harga'),
                                Forms\Components\Select::make('currency')
                                    ->options([
                                        'IDR' => 'Rupiah (IDR)',
                                        'USD' => 'Dollar (USD)',
                                    ])
                                    ->default('IDR')
                                    ->required()
                                    ->label(trans('filament.properties.form.currency')),
                                Forms\Components\TextInput::make('building_size')
                                    ->numeric()
                                    ->required()
                                    ->label(trans('filament.properties.form.building_size'). ' (m²)'),
                                Forms\Components\TextInput::make('land_size')
                                    ->numeric()
                                    ->label(trans('filament.properties.form.land_size'). ' (m²)'),
                                Forms\Components\TextInput::make('bedrooms')
                                    ->integer()
                                    ->label('Kamar Tidur'),
                                Forms\Components\TextInput::make('bathrooms')
                                    ->numeric()
                                    ->label('Kamar Mandi'),
                                Forms\Components\TextInput::make('floors')
                                    ->integer()
                                    ->label('Jumlah Lantai'),
                                Forms\Components\TextInput::make('parking_spots')
                                    ->integer()
                                    ->label('Tempat Parkir'),
                                Forms\Components\Select::make('furnished')
                                    ->options([
                                        'unfurnished' => 'Tidak Furnished',
                                        'semi' => 'Semi Furnished',
                                        'fully' => 'Fully Furnished',
                                    ])
                                    ->label('Status Furnish'),
                                Forms\Components\TextInput::make('year_built')
                                    ->numeric()
                                    ->label('Tahun Dibangun'),
                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Properti Unggulan'),
                                Forms\Components\Toggle::make('is_verified')
                                    ->label('Terverifikasi'),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'active' => 'Aktif',
                                        'pending' => 'Menunggu Persetujuan',
                                        'sold' => 'Terjual',
                                        'rented' => 'Tersewa',
                                    ])
                                    ->default('draft')
                                    ->required()
                                    ->label('Status'),
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Tanggal Publikasi'),
                                Forms\Components\DateTimePicker::make('expires_at')
                                    ->label('Tanggal Kedaluwarsa'),
                                Forms\Components\TextInput::make('virtual_tour_url')
                                    ->url()
                                    ->label('URL Virtual Tour'),
                            ]),
                            
                        Forms\Components\Tabs\Tab::make('Deskripsi')
                            ->icon('heroicon-s-document-text')
                            ->schema([
                                Forms\Components\RichEditor::make('description')
                                    ->required()
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('property-descriptions')
                                    ->columnSpanFull(),
                            ]),
                        Forms\Components\Tabs\Tab::make('propertyAddress')
                            ->label('Alamat')
                            ->icon('heroicon-s-map-pin')
                            ->schema([
                                Forms\Components\Fieldset::make('')
                                    ->relationship('propertyAddress')
                                    ->schema([
                                            GoogleAutocomplete::make('')
                                                ->placesApiNew()
                                                ->withFields([
                                                    Forms\Components\TextInput::make('street_address')
                                                        ->extraInputAttributes([
                                                            'data-google-field' => '{street_number} {route}, {sublocality_level_1}',
                                                        ]),
                                                    Forms\Components\TextInput::make('city')
                                                        ->required()
                                                        ->extraInputAttributes([
                                                            'data-google-field' => 'administrative_area_level_2',
                                                        ])
                                                        ->label('Kabupaten/Kota'),
                                                        
                                                    Forms\Components\TextInput::make('district')
                                                        ->extraInputAttributes([
                                                            'data-google-field' => '{sublocality_level_1}, {sublocality_level_2}, {place_id}, {country}, {route}, {website}',
                                                        ])
                                                        ->label('Kecamatan/Distrik'),
                                                    Forms\Components\TextInput::make('province')
                                                        ->extraInputAttributes([
                                                            'data-google-field' => 'administrative_area_level_1',
                                                        ])
                                                        ->required()
                                                        ->label('Provinsi'),
                                                    Forms\Components\TextInput::make('country')
                                                        ->default('Indonesia')
                                                        ->required()
                                                        ->extraInputAttributes([
                                                            'data-google-field' => 'country',
                                                        ])
                                                        ->label('Negara'),
                                                    Forms\Components\TextInput::make('postal_code')
                                                        ->extraInputAttributes([
                                                            'data-google-field' => 'postal_code',
                                                        ]),
                                                    Forms\Components\TextInput::make('lat')
                                                        ->extraInputAttributes([
                                                            'data-google-field' => 'latitude',
                                                        ])
                                                        ->label('Latitude')
                                                        ->readOnly()
                                                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                                            $lng = $get('lng');
                                                            if ($lng) {
                                                                $set('location', ['lat' => $state, 'lng' => $lng]);
                                                            }
                                                        }),
                                                    
                                                    Forms\Components\TextInput::make('lng')
                                                        ->extraInputAttributes([
                                                            'data-google-field' => 'longitude',
                                                        ])
                                                        ->label('Longitude')
                                                        ->readOnly()
                                                        ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                                            $lat = $get('lat');
                                                            if ($lat) {
                                                                $set('location', ['lat' => $lat, 'lng' => $state]);
                                                            }
                                                        })
                                                        ,
                                                    
                                                ])
                                                ->columnSpanFull(),
                                            Forms\Components\TextInput::make('unit_number')
                                                ->label('Nomor Unit / Rumah'),
                                           
                                            LocationPickr::make('location')
                                                ->columnSpanFull()
                                                ->mapControls([
                                                    'mapTypeControl'    => true,
                                                    'scaleControl'      => true,
                                                    'streetViewControl' => true,
                                                    'rotateControl'     => true,
                                                    'fullscreenControl' => true,
                                                    'zoomControl'       => false,
                                                ])
                                                ->defaultZoom(5)
                                                ->draggable()
                                                ->clickable()
                                                ->height('70vh')
                                                ->defaultLocation([-6.179382, 106.826893])
                                                ->myLocationButtonLabel('My Location')
                                                ->afterStateUpdated(function ($state, callable $set) {
                                                    if (is_array($state) && isset($state['lat']) && isset($state['lng'])) {
                                                        $set('lat', $state['lat']);
                                                        $set('lng', $state['lng']);
                                                    }
                                                })
                                                ->afterStateHydrated(function ($state, callable $set) {
                                                    if (is_array($state) && isset($state['lat']) && isset($state['lng'])) {
                                                        $set('location', $state);
                                                    }
                                                }),
                                            Forms\Components\Toggle::make('display_address')
                                                ->label('Tampilkan Alamat Lengkap')
                                                ->helperText('Jika dinonaktifkan, hanya kota dan provinsi yang akan ditampilkan')
                                                ->default(true),
                                        ]),
                                    ]), 
                        
                        
                                    Forms\Components\Tabs\Tab::make('Media')
                            ->icon('heroicon-s-photo')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('Thumbnail')
                                    ->collection('property-images')
                                    ->multiple()
                                    ->image()
                                    ->imageEditor()
                                    ->imageResizeMode('cover')
                                    ->imageCropAspectRatio('16:9')
                                    ->maxFiles(20)
                                    ->reorderable()
                                    ->columnSpanFull(),
                                Forms\Components\Builder::make('gallery_sections')
                                    ->label('Bagian Galeri')
                                    ->blocks([
                                        Forms\Components\Builder\Block::make('room')
                                            ->label('Ruangan')
                                            ->schema([
                                                Forms\Components\TextInput::make('title')
                                                    ->required()
                                                    ->label('Judul'),
                                                Forms\Components\FileUpload::make('images')
                                                    ->multiple()
                                                    ->image()
                                                    ->required()
                                                    ->directory('properties/rooms'),
                                            ]),
                                    ])
                                    ->columnSpanFull(),
                            ]),
                            
                       
                       
                            Forms\Components\Tabs\Tab::make('Fitur & Fasilitas')
                            ->icon('heroicon-s-star')
                            ->schema([
                                Forms\Components\Select::make('propertyFeatures')
                                    ->label('Fitur Properti')
                                    ->multiple()
                                    ->relationship('features', 'name')
                                    ->columns(3)
                                    ->columnSpanFull(),
                                Forms\Components\Section::make('Fitur Tambahan')
                                    ->schema([
                                        Forms\Components\Repeater::make('custom_features')
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->required()
                                                    ->label('Nama Fitur'),
                                                Forms\Components\TextInput::make('value')
                                                    ->label('Nilai/Deskripsi'),
                                            ])
                                            ->columns(2),
                                    ]),
                            ]),
                            
                        Forms\Components\Tabs\Tab::make('Harga & Riwayat')
                            ->icon('heroicon-s-currency-dollar')
                            ->schema([
                                Forms\Components\Section::make('Riwayat Harga')
                                    ->schema([
                                        Forms\Components\Repeater::make('priceHistory')
                                            ->relationship('priceHistory')
                                            ->schema([
                                                Forms\Components\TextInput::make('price')
                                                    ->required()
                                                    ->numeric()
                                                    ->label('Harga'),
                                                Forms\Components\Select::make('event_type')
                                                    ->options([
                                                        'listed' => 'Listing Awal',
                                                        'price_change' => 'Perubahan Harga',
                                                        'sold' => 'Terjual',
                                                        'rented' => 'Tersewa',
                                                    ])
                                                    ->required()
                                                    ->label('Tipe Perubahan'),
                                                Forms\Components\DateTimePicker::make('event_date')
                                                    ->required()
                                                    ->default(now())
                                                    ->label('Tanggal'),
                                                Forms\Components\TextInput::make('source')
                                                    ->label('Sumber'),
                                                Forms\Components\Textarea::make('notes')
                                                    ->label('Catatan'),
                                            ])
                                            ->defaultItems(fn ($get) => $get('id') ? 0 : 1)
                                            ->itemLabel(fn (array $state): ?string => 
                                                ($state['event_type'] ?? null) && ($state['price'] ?? null) 
                                                    ? ucfirst($state['event_type']) . ': Rp ' . number_format($state['price'], 0, ',', '.') . ' - ' . ($state['event_date'] ?? 'Tanggal belum diatur')
                                                    : null
                                            )
                                            ->addActionLabel('Tambah Riwayat Harga')
                                            ->columns(2),
                                    ]),
                                Forms\Components\Section::make('Estimasi Nilai')
                                    ->schema([
                                        Forms\Components\Repeater::make('valueEstimates')
                                            ->relationship('valueEstimates')
                                            ->schema([
                                                Forms\Components\TextInput::make('estimated_value')
                                                    ->required()
                                                    ->numeric()
                                                    ->label('Estimasi Nilai')
                                                    ->suffixIcon('heroicon-s-currency-dollar'),
                                                Forms\Components\DateTimePicker::make('estimate_date')
                                                    ->required()
                                                    ->default(now())
                                                    ->label('Tanggal Estimasi'),
                                                Forms\Components\TextInput::make('confidence_score')
                                                    ->numeric()
                                                    ->label('Skor Kepercayaan (%)')
                                                    ->suffix('%')
                                                    ->maxValue(100)
                                                    ->default(85),
                                                Forms\Components\TextInput::make('high_estimate')
                                                    ->numeric()
                                                    ->label('Estimasi Tinggi')
                                                    ->suffixIcon('heroicon-s-currency-dollar'),
                                                Forms\Components\TextInput::make('low_estimate')
                                                    ->numeric()
                                                    ->label('Estimasi Rendah')
                                                    ->suffixIcon('heroicon-s-currency-dollar'),
                                                Forms\Components\Textarea::make('factors')
                                                    ->label('Faktor-faktor')
                                                    ->helperText('Masukkan dalam format JSON atau teks biasa')
                                                    ->columnSpan(2),
                                                Forms\Components\Select::make('created_by')
                                                    ->options([
                                                        'system' => 'Sistem',
                                                        'agent' => 'Agen',
                                                        'appraiser' => 'Penilai',
                                                        'manual' => 'Manual',
                                                    ])
                                                    ->default('manual')
                                                    ->required()
                                                    ->label('Dibuat Oleh'),
                                            ])
                                            ->maxItems(1)
                                            ->columns(2)
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                            
                        Forms\Components\Tabs\Tab::make('Dokumen')
                            ->icon('heroicon-s-document-duplicate')
                            ->schema([
                                Forms\Components\Repeater::make('documents')
                                    ->relationship('documents')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required()
                                            ->maxLength(255)
                                            ->label('Judul Dokumen'),
                                        Forms\Components\Textarea::make('description')
                                            ->label('Deskripsi'),
                                        Forms\Components\SpatieMediaLibraryFileUpload::make('document')
                                            ->collection('property-documents')
                                            ->disk('media')
                                            ->directory('property-documents')
                                            ->acceptedFileTypes(['application/pdf', 'image/*', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                            ->required()
                                            ->label('File Dokumen'),
                                        Forms\Components\Toggle::make('is_public')
                                            ->label('Dokumen Publik')
                                            ->helperText('Jika diaktifkan, dokumen ini akan terlihat oleh semua pengunjung')
                                            ->default(false),
                                    ])
                                    ->itemLabel(fn (array $state): ?string => $state['title'] ?? null)
                                    ->addActionLabel('Tambah Dokumen')
                                    ->columns(2),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Nama Properti')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('property_type')->badge()->color(fn (PropertyType $state) => $state->color())->formatStateUsing(fn (PropertyType $state) => $state->label()),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (Status $state) => $state->getColor())->formatStateUsing(fn (Status $state) => $state->getLabel()),
                Tables\Columns\TextColumn::make('price')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(PropertyStatus::class),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProperties::route('/'),
            'create' => Pages\CreateProperties::route('/create'),
            'view' => Pages\ViewProperties::route('/{record}'),
            'edit' => Pages\EditProperties::route('/{record}/edit'),
        ];
    }
}
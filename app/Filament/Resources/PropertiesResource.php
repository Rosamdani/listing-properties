<?php

namespace App\Filament\Resources;

use App\Enum\Property\Status;
use App\Enum\PropertyStatus;
use App\Enum\PropertyType;
use App\Filament\Resources\PropertiesResource\Pages;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Models\Properties;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use RalphJSmit\Filament\SEO\SEO;

class PropertiesResource extends Resource
{
    protected static ?string $model = Properties::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('PropertiesTabs')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Informasi Dasar')
                            ->label(trans('filament.properties.form.basic_information'))
                            ->icon('heroicon-o-home')
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
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\RichEditor::make('description')
                                    ->required()
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('property-descriptions')
                                    ->columnSpanFull(),
                            ]),
                        Forms\Components\Tabs\Tab::make('propertyAddress')
                            ->label('Alamat')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                Forms\Components\Fieldset::make('')
                                    ->relationship('propertyAddress')
                                    ->schema([
                                            Forms\Components\TextInput::make('street_address')
                                                ->label('Alamat Jalan')
                                                ->columnSpanFull(),
                                            Forms\Components\TextInput::make('unit_number')
                                                ->label('Nomor Unit / Rumah'),
                                            Forms\Components\TextInput::make('city')
                                                ->required()
                                                ->label('Kota'),
                                            Forms\Components\TextInput::make('district')
                                                ->label('Kecamatan/Distrik'),
                                            Forms\Components\TextInput::make('province')
                                                ->required()
                                                ->label('Provinsi'),
                                            Forms\Components\TextInput::make('postal_code')
                                                ->label('Kode Pos'),
                                            Forms\Components\TextInput::make('country')
                                                ->default('Indonesia')
                                                ->required()
                                                ->label('Negara'),
                                            Forms\Components\Grid::make(2)
                                                ->schema([
                                                    Forms\Components\TextInput::make('latitude')
                                                        ->numeric()
                                                        ->label('Latitude'),
                                                    Forms\Components\TextInput::make('longitude')
                                                        ->numeric()
                                                        ->label('Longitude'),
                                                ]),
                                            Forms\Components\Toggle::make('display_address')
                                                ->label('Tampilkan Alamat Lengkap')
                                                ->helperText('Jika dinonaktifkan, hanya kota dan provinsi yang akan ditampilkan')
                                                ->default(true),
                                        ]),
                                    ]),
                        
                        Forms\Components\Tabs\Tab::make('Media')
                            ->icon('heroicon-o-photo')
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
                            ->icon('heroicon-o-star')
                            ->schema([
                                Forms\Components\CheckboxList::make('propertyFeatures')
                                    ->label('Fitur Properti')
                                    ->relationship('features', 'name')
                                    ->columns(3)
                                    ->bulkToggleable()
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
                            ->icon('heroicon-o-currency-dollar')
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
                                                    ->suffixIcon('heroicon-o-currency-dollar'),
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
                                                    ->suffixIcon('heroicon-o-currency-dollar'),
                                                Forms\Components\TextInput::make('low_estimate')
                                                    ->numeric()
                                                    ->label('Estimasi Rendah')
                                                    ->suffixIcon('heroicon-o-currency-dollar'),
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
                            ->icon('heroicon-o-document-duplicate')
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
                        
                        Forms\Components\Tabs\Tab::make('SEO')
                            ->icon('heroicon-o-globe-alt')
                            ->schema([
                                SEO::make(),
                            ]),
                    
                        Forms\Components\Tabs\Tab::make('Analitik')
                            ->icon('heroicon-o-chart-bar')
                            ->schema([
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Placeholder::make('views_total')
                                            ->label('Total Dilihat')
                                            ->content(fn ($record) => $record && $record->analytics ? $record->analytics->views_total : 0),
                                        Forms\Components\Placeholder::make('views_last_7d')
                                            ->label('Dilihat 7 Hari Terakhir')
                                            ->content(fn ($record) => $record && $record->analytics ? $record->analytics->views_last_7d : 0),
                                        Forms\Components\Placeholder::make('views_last_30d')
                                            ->label('Dilihat 30 Hari Terakhir')
                                            ->content(fn ($record) => $record && $record->analytics ? $record->analytics->views_last_30d : 0),
                                    ])
                                    ->columns(3),
                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Placeholder::make('saves_total')
                                            ->label('Total Disimpan')
                                            ->content(fn ($record) => $record && $record->analytics ? $record->analytics->saves_total : 0),
                                        Forms\Components\Placeholder::make('inquiries_total')
                                            ->label('Total Pertanyaan')
                                            ->content(fn ($record) => $record && $record->analytics ? $record->analytics->inquiries_total : 0),
                                        Forms\Components\Placeholder::make('listing_quality_score')
                                            ->label('Skor Kualitas Listing')
                                            ->content(function ($record) {
                                                if (!$record || !$record->analytics || !$record->analytics->listing_quality_score) {
                                                    return 'Belum dinilai';
                                                }
                                                
                                                $score = $record->analytics->listing_quality_score;
                                                
                                                // Return formatted score with appropriate color
                                                if ($score >= 4.5) {
                                                    return '<span class="text-success font-bold">' . $score . ' - Sangat Baik</span>';
                                                } elseif ($score >= 3.5) {
                                                    return '<span class="text-primary font-medium">' . $score . ' - Baik</span>';
                                                } elseif ($score >= 2.5) {
                                                    return '<span class="text-warning font-medium">' . $score . ' - Cukup</span>';
                                                } else {
                                                    return '<span class="text-danger font-medium">' . $score . ' - Perlu Perbaikan</span>';
                                                }
                                            }),
                                    ])
                                    ->columns(3),
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
            'edit' => Pages\EditProperties::route('/{record}/edit'),
            'view' => Pages\ViewProperties::route('/{record}'),
        ];
    }
}

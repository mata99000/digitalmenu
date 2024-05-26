<section class="section">
    @foreach($categories as $category)
    <div class="w-layout-grid grid">
        <a href="/category/{{ $category->id }}" class="w-inline-block">
            <img 
                src="{{ asset('storage/' . $category->image) }}" 
                loading="lazy" 
                width="300" 
                alt="{{ $category->name }}" 
                srcset="{{ asset('storage/' . $category->image) }} 500w, {{ asset('storage/' . $category->image) }} 600w" 
                sizes="(max-width: 479px) 40vw, (max-width: 767px) 24vw, 28vw" 
                class="image"
            />
        </a>
        <a id="w-node-_374043ad-d13e-6015-3876-161303f403dd-6c5071c2" href="/category/{{ $category->id }}" class="link-block w-inline-block">
            <div class="text-block-3">
                <span class="text-span-3">{{ $category->name }}</span>
            </div>
        </a>
    </div>
@endforeach
 
</section>


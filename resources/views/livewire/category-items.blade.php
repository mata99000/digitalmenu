<div>
    <body class="body-2">


        <div>
            @if($category)
                <div>
                    @foreach ($subcategories as $subcategory)
                        <div class="w-layout-blockcontainer container-4 w-container">
                            <h1 class="heading-7">{{ $subcategory->name }}</h1>
                            
                            @foreach ($subcategory->items as $item)
                                <div class="w-layout-grid grid-4">
                                    <img src="{{ asset('storage/' . $item->image) }}" loading="lazy" id="w-node-_1cdb4422-9e88-8eec-16d3-b17441b597fd-879c8c16" alt="" class="image-2"/>
                                    <div id="w-node-_7bd7dbe9-6f4f-0958-1deb-f95720b83c50-879c8c16" class="w-layout-blockcontainer w-container">
                                        <h4 class="heading-6">{{ $item->name }}</h4>
                                        <div id="w-node-_12538e8e-8289-bb8b-e290-deb294293e81-879c8c16" class="text-block-5">{{ $item->comment }}</div>
                                    </div>
                                    <h4 id="w-node-e9f677ae-3b19-3ca3-c05e-5fa9a165e7c9-879c8c16" class="heading-6">{{ $item->price }}.00</h4>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @else
                <p>Category not found.</p>
            @endif
        </div>
        
    
    
    </body>

</div>
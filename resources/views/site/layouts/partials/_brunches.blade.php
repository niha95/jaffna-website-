

   <section class="client-section">
            <div class="container">
                <h2 class="text-center">{{trans('labels.Clients')}}Clients</h2>
                <ul class="list-inline list-unstyled">
                     @foreach($featuredPages as $package) 
                    <li>
                       
                            @if($package->featuredImage)<img class="img-responsive" src="{{ $package->featuredImage->imageFullLink() }}" alt="icon">@endif
                     
                    </li>
               @endforeach
                </ul>
            </div>
        </section>
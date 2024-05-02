@php
    $darkMode = (bool) setting()->getForCurrentUser('dark-mode-enabled');
@endphp


<h5>{{ trans('Space Navigation') }}</h5>
@if(count($entities) > 0)
    <ul class="sidebar-navigation {{ $darkMode ? 'dark-mode' : 'light-mode' }}">
        @foreach($entities as $space)
        <li class="entity space {{ request()->is('shelves/'.$space->slug) ? 'active-tab' : '' }}">
            <a href="{{ $space->getUrl() }}" style="border-left-color: #a94747;"  class="space entity-link-space toggle">
                <span class="entity-name">{{ $space->name }}</span>
            </a>
                @if(count($space->books) > 0)
                    <ul class="nested-entities books {{ request()->is('books/'.$space->slug) ? 'active-tab-book' : '' }} ">
                        @foreach($space->books as $book)
                            <li class="entity book  {{ request()->is('books/'.$book->slug) ? 'active-tab' : '' }}">
                                <a href="{{ $book->getUrl() }}" style="border-left-color: #077b70;"  class="book entity-link-book toggle">
                                    {{-- <span role="presentation" class="icon">@icon('book')</span> --}}
                                    <span class="entity-name">{{ $book->name }}</span>
                                </a>
                                {{-- <div class="toggle-arrow" onclick="toggleEntity(this)"></div> --}}
                                <ul class="nested-entities chapters">
                                        @if(count($book->chapters) > 0)
                                        @foreach($book->chapters as $chapter)
                                        <li class="entity chapter">
                                                <a href="{{ $chapter->getUrl() }}" style="border-left-color: #af4d0d;"  class="chapter entity-link-chapter toggle">
                                                    {{-- <span role="presentation" class="icon">@icon('chapter')</span> --}}
                                                    <span class="entity-name">{{ $chapter->name }}</span>
                                                </a>
                                                @if(count($chapter->pages) > 0)
                                                    <ul class="nested-entities pages">
                                                        @foreach($chapter->pages as $page)
                                                            <li class="entity page">
                                                                <a href="{{ $page->getUrl() }}" style="border-left-color: #206ea7;"  class="page entity-link-page toggle">
                                                                    {{-- <span role="presentation" class="icon">@icon('page')</span> --}}
                                                                    <span class="entity-name">{{ $page->name }}</span>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                        </li>
                                        @endforeach
                                        @endif
                                        @if(count($book->directPages) > 0) 
                                        @foreach($book->directPages as $directPage)
                                           <li class="entity chapter">
                                               <a href="{{ $directPage->getUrl() }}" style="border-left-color: #206ea7;" class="page entity-link-page toggle">
                                                   <span class="entity-name">{{ $directPage->name }}</span>
                                               </a>
                                           </li>
                                        @endforeach
                                        @endif
                                       
                                    </ul>
                                 
                                      
                            
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endforeach
    </ul>
@else
    <p class="text-muted empty-text">{{ $emptyText ?? trans('common.no_items') }}</p>
@endif


<style>
       .sidebar-navigation, 
    .sidebar-navigation ul, 
    .sidebar-navigation ul li, 
    .sidebar-navigation .nested-entities, 
    .sidebar-navigation .nested-entities li {
        list-style-type: none !important; /* Removes bullets from all lists, ensuring override */
    }

.sidebar-navigation {
    font-family: Arial, sans-serif; /* Ensures a clean, modern look */
    padding: 10px 0; /* Adds padding to the top and bottom of the navigation */
    margin: 0;
    font-size: 14px; /* Adjusts font size for better readability */
    width: 100%; /* Ensures the nav takes full available width */
    box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Adds subtle shadow for depth */
  
}

.entity-link-space {
    padding: 10px 15px; /* Provides ample padding for touch targets and spacing */
    display: block; /* Makes the link extend the full width of the container */
    color: #333; /* Sets text color */
    text-decoration: none; /* Removes underline from links */
    border-left: 3px solid transparent; /* Adds a subtle indicator for hierarchy */
    border-width: 4px
}
.entity-link-book {
    padding: 10px 15px; /* Provides ample padding for touch targets and spacing */
    display: block; /* Makes the link extend the full width of the container */
    color: #333; /* Sets text color */
    text-decoration: none; /* Removes underline from links */
    border-left: 3px solid transparent; /* Adds a subtle indicator for hierarchy */
    border-width: 4px
}
.entity-link-chapter {
    padding: 10px 15px; /* Provides ample padding for touch targets and spacing */
    display: block; /* Makes the link extend the full width of the container */
    color: #333; /* Sets text color */
    text-decoration: none; /* Removes underline from links */
    border-left: 3px solid transparent; /* Adds a subtle indicator for hierarchy */
    border-width: 4px
}
.entity-link-page {
    padding: 10px 15px; /* Provides ample padding for touch targets and spacing */
    display: block; /* Makes the link extend the full width of the container */
    color: #333; /* Sets text color */
    text-decoration: none; /* Removes underline from links */
    border-left: 3px solid transparent; /* Adds a subtle indicator for hierarchy */
    border-width: 4px
}

.entity-link:hover, .entity-link.selected {
    background-color: #ececec; /* Light grey background on hover/selection */
    border-left-color: #000000; /* Blue highlight indicator */
}

.toggle-arrow {
    float: right; /* Positions the arrow to the right */
    transition: transform 0.3s ease; /* Smooth transition for arrow rotation */
}

.expanded .toggle-arrow {
    transform: rotate(-90deg); /* Rotates arrow when expanded */
}

/* Additional styles for dark mode */
.dark-mode .sidebar-navigation {
        color: #ccc; /* Lighter text for dark backgrounds */
    }

    .dark-mode .entity-link-space,
    .dark-mode .entity-link-book,
    .dark-mode .entity-link-chapter,
    .dark-mode .entity-link-page {
        color: #ddd; /* Lighter text for links */
    }

    .light-mode .sidebar-navigation {
        color: #333; /* Darker text for light backgrounds */
    }

    .light-mode .entity-link-space,
    .light-mode .entity-link-book,
    .light-mode .entity-link-chapter,
    .light-mode .entity-link-page {
        color: #333; /* Standard text color for links */
    }
</style>

             <script nonce="{{ $cspNonce }}">
                document.addEventListener('DOMContentLoaded', function() {
                    // Initially hide all nested entities
                    document.querySelectorAll('.nested-entities').forEach(function(list) {
                        list.style.display = 'none'; // Ensures all are collapsed by default
                    });
                
                    // Generic toggle function for nested entities
                    function toggleNestedEntities(toggle) {
                        const nextUl = toggle.nextElementSibling;
                        if (nextUl && nextUl.classList.contains('nested-entities')) {
                            const isExpanded = nextUl.style.display === 'block';
                            // Collapse all other nested entities at the same level to ensure only one is expanded at a time
                            const parentEntities = toggle.closest('.entity').parentNode;
                            const otherNestedEntities = parentEntities.querySelectorAll('.nested-entities');
                            otherNestedEntities.forEach(function(entity) {
                                if (entity !== nextUl) {
                                    entity.style.display = 'none';
                                    const prevToggle = entity.previousElementSibling;
                                    if (prevToggle && prevToggle.classList.contains('expanded')) {
                                        prevToggle.classList.remove('expanded');
                                        prevToggle.classList.remove('open-page'); // Remove class when collapsing other books
                                    }
                                }
                            });
                
                            // Toggle the current nested entity
                            nextUl.style.display = isExpanded ? 'none' : 'block';
                           
                        }
                    }
                
                    // Attach event listeners to all toggle elements
                    document.querySelectorAll('.toggle').forEach(function(toggle) {
                        toggle.addEventListener('click', function(event) {
                            event.preventDefault();
                            event.stopPropagation(); // Stops the event from bubbling up to prevent unwanted behavior
                            toggleNestedEntities(toggle);
                        });
                    });
                    // Auto-expand the matching slug if present


                    // Attempt to find an active space or book toggle element
                    const activeToggle = document.querySelector('.active-tab .toggle') 

// Check if an element was found and call the function to expand it
    if (activeToggle) {
    toggleNestedEntities(activeToggle); // Auto expand the found entity
    // Assuming activeToggle is already defined and points to an element in the DOM
const parentEntities = activeToggle.closest('.books');

// Check if parentEntities actually exists to avoid errors
if (parentEntities) {
    // Access and possibly modify the display property
    const currentDisplay = parentEntities.style.display; // Get the current display value
    console.log('Current display:', currentDisplay); // Optional: log current display

    // Change the display property to 'block' or another value as needed
    parentEntities.style.display = 'block'; // Modify the display property

    // Optionally log the change
    console.log('Updated display:', parentEntities.style.display);
} else {
    console.log('No parent entity with class .books found');
}

}

                    // Double click for redirection if necessary
                    document.querySelectorAll('.toggle').forEach(function(toggle) {
                        toggle.addEventListener('dblclick', function(event) {
                            window.location.href = toggle.getAttribute('href');
                        });
                    });
                });
                </script>
                
@props(['show' => false])

<div x-data="{
    show: {{ $show ? 'true' : 'false' }},
    categories: [],
    selectedInterests: [],
    loading: false,
    submitting: false,

    async init() {
        if (this.show) {
            this.loading = true;
            try {
                // We can fetch categories from an API or pass them via props.
                // For simplicity, let's assume we can fetch them from the search API or a new endpoint.
                // Or better, let's pass them from the controller if possible, but to keep it clean,
                // let's fetch from the existing search API (hacky) or just use a new simple endpoint.
                // Actually, let's just use the search API with empty query to get some, OR
                // since we are in blade, we can just pass the categories if we had them.
                // But wait, we didn't pass all categories to the view.
                // Let's use a quick fetch to the categories resource or just embed them if small.
                // Let's fetch from a new simple API endpoint or just use what we have.
                // Let's create a quick API endpoint for all categories? No, let's use the existing search API?
                // No, let's just pass all categories from the controller to the view?
                // That might be heavy if there are thousands.
                // Let's assume there aren't many for now and fetch them via a simple script tag or API.
                // Let's use a simple fetch to a new route or just use the search suggestions API with a wildcard?
                // Let's just use the search API for now, or better, let's add a route to get all categories.
                // Wait, I can just use the existing search suggestions API with a specific type?
                // Let's just add a small script to the view to pass categories?
                // No, let's do it properly. I'll add a route to get categories.
                
                const response = await fetch('{{ route('api.search.suggestions') }}?q=a'); // Hacky
                // Okay, let's just pass them from the controller. It's cleaner.
                // I will update the controller to pass 'allCategories' to the view.
            } catch (error) {
                console.error('Error loading categories:', error);
            } finally {
                this.loading = false;
            }
        }
    },

    async submit() {
        if (this.selectedInterests.length === 0) return;

        this.submitting = true;
        try {
            const response = await fetch('{{ route('interests.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    interests: this.selectedInterests
                })
            });

            if (response.ok) {
                this.show = false;
                window.location.reload();
            }
        } catch (error) {
            console.error('Error saving interests:', error);
        } finally {
            this.submitting = false;
        }
    }
}"
x-show="show"
style="display: none;"
class="fixed inset-0 z-50 overflow-y-auto"
aria-labelledby="modal-title" role="dialog" aria-modal="true">

    <!-- Backdrop -->
    <div x-show="show"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal Panel -->
        <div x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Personalize Your Feed
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 mb-4">
                                Select topics you are interested in to help us recommend the best stories for you.
                            </p>
                            
                            <!-- Categories Grid -->
                            <div class="grid grid-cols-2 gap-3 max-h-60 overflow-y-auto p-1">
                                @foreach(\App\Models\Category::all() as $category)
                                    <label class="relative flex items-start py-2 px-3 rounded-lg border cursor-pointer hover:bg-gray-50 transition-colors"
                                           :class="selectedInterests.includes({{ $category->id }}) ? 'border-indigo-500 bg-indigo-50 ring-1 ring-indigo-500' : 'border-gray-200'">
                                        <div class="min-w-0 flex-1 text-sm">
                                            <div class="font-medium text-gray-700 select-none">
                                                {{ $category->name }}
                                            </div>
                                        </div>
                                        <div class="ml-3 flex items-center h-5">
                                            <input type="checkbox" 
                                                   value="{{ $category->id }}" 
                                                   x-model="selectedInterests"
                                                   class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" 
                        @click="submit" 
                        :disabled="submitting || selectedInterests.length === 0"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!submitting">Save Interests</span>
                    <span x-show="submitting">Saving...</span>
                </button>
            </div>
        </div>
    </div>
</div>

@props(['productId'])

<div class="product-rating" data-product-id="{{ $productId }}" x-data="productRating()">
    <h3 class="text-xl font-semibold mb-4">Valoraciones</h3>
    
    <div class="mb-6">
        <div class="flex items-center mb-2">
            <div class="flex">
                <template x-for="i in 5">
                    <span 
                        class="text-2xl cursor-pointer" 
                        :class="i <= rating ? 'text-yellow-500' : 'text-gray-300'"
                        @click="setRating(i)"
                        x-text="'★'"
                    ></span>
                </template>
            </div>
            <span class="ml-2 text-gray-700" x-text="ratingText"></span>
        </div>
        <textarea 
            x-model="comment" 
            class="w-full p-2 border rounded focus:ring focus:ring-orange-300"
            placeholder="Escribe tu comentario..."
            maxlength="150"
        ></textarea>
        <div class="text-right text-sm text-gray-500" x-text="comment.length + '/150'"></div>
        <button 
            @click="submitRating"
            class="mt-2 bg-orange-500 text-white px-4 py-2 rounded hover:bg-orange-600 transition duration-200"
            :disabled="isSubmitting"
        >
            <span x-show="!isSubmitting">Enviar valoración</span>
            <span x-show="isSubmitting">
                <i class="fas fa-circle-notch fa-spin"></i> Enviando...
            </span>
        </button>
    </div>
    
    <div class="space-y-4" x-show="opinions.length > 0">
        <template x-for="opinion in opinions" :key="opinion.id">
            <div class="bg-gray-50 p-4 rounded border">
                <div class="flex items-center mb-2">
                    <div class="flex">
                        <template x-for="i in 5">
                            <span 
                                class="text-xl" 
                                :class="i <= opinion.rating ? 'text-yellow-500' : 'text-gray-300'"
                                x-text="'★'"
                            ></span>
                        </template>
                    </div>
                    <span class="ml-2 font-semibold" x-text="opinion.userName"></span>
                </div>
                <p class="text-gray-700" x-text="opinion.comment"></p>
                <div class="text-sm text-gray-500 mt-2" x-text="formatDate(opinion.date)"></div>
            </div>
        </template>
    </div>
    
    <div x-show="opinions.length === 0" class="text-gray-500 text-center py-4">
        No hay valoraciones para este producto. ¡Sé el primero en opinar!
    </div>
</div>

@push('scripts')
<script>
function productRating() {
    return {
        productId: {{ $productId }},
        rating: 0,
        comment: '',
        opinions: [],
        isSubmitting: false,
        
        init() {
            this.fetchOpinions();
        },
        
        get ratingText() {
            const texts = ['', 'Muy malo', 'Malo', 'Regular', 'Bueno', 'Excelente'];
            return texts[this.rating];
        },
        
        setRating(value) {
            this.rating = value;
        },
        
        async fetchOpinions() {
            try {
                const response = await fetch(`/api/opinions/${this.productId}`);
                const data = await response.json();
                this.opinions = data;
            } catch (error) {
                console.error('Error fetching opinions:', error);
            }
        },
        
        async submitRating() {
            if (this.rating === 0) {
                alert('Por favor, selecciona una valoración');
                return;
            }
            
            this.isSubmitting = true;
            
            try {
                const response = await fetch('/api/opinions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        productId: this.productId,
                        rating: this.rating,
                        comment: this.comment
                    })
                });
                
                if (response.ok) {
                    this.comment = '';
                    this.rating = 0;
                    await this.fetchOpinions();
                } else {
                    const error = await response.json();
                    alert(error.message || 'Ha ocurrido un error al enviar tu valoración');
                }
            } catch (error) {
                console.error('Error submitting rating:', error);
                alert('Ha ocurrido un error al enviar tu valoración');
            } finally {
                this.isSubmitting = false;
            }
        },
        
        formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }
    }
}
</script>
@endpush
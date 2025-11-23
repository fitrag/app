<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6" x-data="seoAnalyzer()">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">SEO Analysis</h3>
        <div class="flex items-center gap-2">
            <div class="text-2xl font-bold" :class="{
                'text-green-600': overallScore >= 80,
                'text-yellow-600': overallScore >= 50 && overallScore < 80,
                'text-red-600': overallScore < 50
            }" x-text="overallScore + '%'"></div>
        </div>
    </div>

    <!-- Overall Score Bar -->
    <div class="mb-6">
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="h-3 rounded-full transition-all duration-300" 
                 :class="{
                     'bg-green-500': overallScore >= 80,
                     'bg-yellow-500': overallScore >= 50 && overallScore < 80,
                     'bg-red-500': overallScore < 50
                 }"
                 :style="'width: ' + overallScore + '%'"></div>
        </div>
        <p class="text-xs text-gray-500 mt-2" x-text="getScoreLabel()"></p>
    </div>

    <!-- Focus Keyword Input -->
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Focus Keyword</label>
        <input type="text" 
               name="focus_keyword"
               x-model="focusKeyword"
               @input.debounce.500ms="analyze()"
               value="{{ old('focus_keyword', $post->focus_keyword ?? '') }}"
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
               placeholder="e.g., Laravel Tutorial">
        <p class="mt-1 text-xs text-gray-500">Enter your target keyword for SEO optimization</p>
    </div>

    <!-- SEO Checks -->
    <div class="space-y-3">
        <!-- Title Check -->
        <div class="flex items-start gap-3 p-3 rounded-lg" :class="{
            'bg-green-50': checks.title.status === 'good',
            'bg-yellow-50': checks.title.status === 'warning',
            'bg-red-50': checks.title.status === 'poor'
        }">
            <div class="flex-shrink-0 mt-0.5">
                <template x-if="checks.title.status === 'good'">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </template>
                <template x-if="checks.title.status === 'warning'">
                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </template>
                <template x-if="checks.title.status === 'poor'">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </template>
            </div>
            <div class="flex-1">
                <div class="font-medium text-sm text-gray-900">Title Optimization</div>
                <div class="text-xs text-gray-600 mt-1" x-text="checks.title.message"></div>
            </div>
        </div>

        <!-- Content Length Check -->
        <div class="flex items-start gap-3 p-3 rounded-lg" :class="{
            'bg-green-50': checks.content.status === 'good',
            'bg-yellow-50': checks.content.status === 'warning',
            'bg-red-50': checks.content.status === 'poor'
        }">
            <div class="flex-shrink-0 mt-0.5">
                <template x-if="checks.content.status === 'good'">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </template>
                <template x-if="checks.content.status === 'warning'">
                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </template>
                <template x-if="checks.content.status === 'poor'">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </template>
            </div>
            <div class="flex-1">
                <div class="font-medium text-sm text-gray-900">Content Length & Readability</div>
                <div class="text-xs text-gray-600 mt-1" x-text="checks.content.message"></div>
            </div>
        </div>

        <!-- Keyword Density Check -->
        <div class="flex items-start gap-3 p-3 rounded-lg" :class="{
            'bg-green-50': checks.keyword.status === 'good',
            'bg-yellow-50': checks.keyword.status === 'warning',
            'bg-red-50': checks.keyword.status === 'poor'
        }">
            <div class="flex-shrink-0 mt-0.5">
                <template x-if="checks.keyword.status === 'good'">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </template>
                <template x-if="checks.keyword.status === 'warning'">
                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </template>
                <template x-if="checks.keyword.status === 'poor'">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </template>
            </div>
            <div class="flex-1">
                <div class="font-medium text-sm text-gray-900">Keyword Usage</div>
                <div class="text-xs text-gray-600 mt-1" x-text="checks.keyword.message"></div>
            </div>
        </div>

        <!-- Heading Structure Check -->
        <div class="flex items-start gap-3 p-3 rounded-lg" :class="{
            'bg-green-50': checks.headings.status === 'good',
            'bg-yellow-50': checks.headings.status === 'warning',
            'bg-red-50': checks.headings.status === 'poor'
        }">
            <div class="flex-shrink-0 mt-0.5">
                <template x-if="checks.headings.status === 'good'">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </template>
                <template x-if="checks.headings.status === 'warning'">
                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </template>
                <template x-if="checks.headings.status === 'poor'">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </template>
            </div>
            <div class="flex-1">
                <div class="font-medium text-sm text-gray-900">Heading Structure</div>
                <div class="text-xs text-gray-600 mt-1" x-text="checks.headings.message"></div>
            </div>
        </div>

        <!-- Image Alt Text Check -->
        <div class="flex items-start gap-3 p-3 rounded-lg" :class="{
            'bg-green-50': checks.images.status === 'good',
            'bg-yellow-50': checks.images.status === 'warning',
            'bg-red-50': checks.images.status === 'poor'
        }">
            <div class="flex-shrink-0 mt-0.5">
                <template x-if="checks.images.status === 'good'">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </template>
                <template x-if="checks.images.status === 'warning'">
                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </template>
                <template x-if="checks.images.status === 'poor'">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </template>
            </div>
            <div class="flex-1">
                <div class="font-medium text-sm text-gray-900">Image Optimization</div>
                <div class="text-xs text-gray-600 mt-1" x-text="checks.images.message"></div>
            </div>
        </div>

        <!-- Links Check -->
        <div class="flex items-start gap-3 p-3 rounded-lg" :class="{
            'bg-green-50': checks.links.status === 'good',
            'bg-yellow-50': checks.links.status === 'warning',
            'bg-red-50': checks.links.status === 'poor'
        }">
            <div class="flex-shrink-0 mt-0.5">
                <template x-if="checks.links.status === 'good'">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </template>
                <template x-if="checks.links.status === 'warning'">
                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </template>
                <template x-if="checks.links.status === 'poor'">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </template>
            </div>
            <div class="flex-1">
                <div class="font-medium text-sm text-gray-900">Internal & External Links</div>
                <div class="text-xs text-gray-600 mt-1" x-text="checks.links.message"></div>
            </div>
        </div>
    </div>
</div>

<script>
function seoAnalyzer() {
    return {
        focusKeyword: '{{ old('focus_keyword', $post->focus_keyword ?? '') }}',
        title: '',
        content: '',
        overallScore: 0,
        checks: {
            title: { status: 'poor', message: 'Waiting for title...' },
            content: { status: 'poor', message: 'Waiting for content...' },
            keyword: { status: 'poor', message: 'Enter a focus keyword to analyze' },
            headings: { status: 'poor', message: 'Waiting for content...' },
            images: { status: 'poor', message: 'Waiting for content...' },
            links: { status: 'poor', message: 'Waiting for content...' }
        },

        init() {
            this.$nextTick(() => {
                this.title = document.querySelector('input[name="title"]')?.value || '';
                
                if (window.editor) {
                    this.content = window.editor.getData();
                }
                
                this.analyze();
                
                document.querySelector('input[name="title"]')?.addEventListener('input', (e) => {
                    this.title = e.target.value;
                    this.analyze();
                });
                
                if (window.editor) {
                    window.editor.model.document.on('change:data', () => {
                        this.content = window.editor.getData();
                        this.analyze();
                    });
                }
            });
        },

        analyze() {
            this.analyzeTitle();
            this.analyzeContent();
            this.analyzeKeyword();
            this.analyzeHeadings();
            this.analyzeImages();
            this.analyzeLinks();
            this.calculateOverallScore();
        },

        analyzeTitle() {
            const length = this.title.length;
            const hasKeyword = this.focusKeyword && this.title.toLowerCase().includes(this.focusKeyword.toLowerCase());
            
            if (length === 0) {
                this.checks.title = { status: 'poor', message: 'Title is empty' };
            } else if (length >= 50 && length <= 60 && hasKeyword) {
                this.checks.title = { status: 'good', message: `Perfect! ${length} characters with keyword` };
            } else if (length >= 40 && length <= 70) {
                if (hasKeyword) {
                    this.checks.title = { status: 'warning', message: `${length} characters - try 50-60 for best results` };
                } else {
                    this.checks.title = { status: 'warning', message: `${length} characters - add your focus keyword` };
                }
            } else {
                this.checks.title = { status: 'poor', message: `${length} characters - aim for 50-60 characters` };
            }
        },

        analyzeContent() {
            const text = this.content.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
            const words = text.split(' ').filter(word => word.length > 0);
            const wordCount = words.length;
            const sentences = text.split(/[.!?]+/).filter(s => s.trim().length > 0);
            const avgWordsPerSentence = sentences.length > 0 ? wordCount / sentences.length : 0;
            
            let readability = '';
            if (avgWordsPerSentence < 15) {
                readability = 'Easy to read';
            } else if (avgWordsPerSentence < 20) {
                readability = 'Moderate';
            } else {
                readability = 'Complex - consider shorter sentences';
            }
            
            if (wordCount === 0) {
                this.checks.content = { status: 'poor', message: 'Content is empty' };
            } else if (wordCount >= 300) {
                this.checks.content = { status: 'good', message: `Great! ${wordCount} words. ${readability}` };
            } else if (wordCount >= 150) {
                this.checks.content = { status: 'warning', message: `${wordCount} words - aim for at least 300. ${readability}` };
            } else {
                this.checks.content = { status: 'poor', message: `Only ${wordCount} words - add more content (min 300)` };
            }
        },

        analyzeKeyword() {
            if (!this.focusKeyword) {
                this.checks.keyword = { status: 'poor', message: 'Enter a focus keyword to analyze' };
                return;
            }
            
            const text = this.content.replace(/<[^>]*>/g, ' ').toLowerCase();
            const words = text.split(/\s+/).filter(word => word.length > 0);
            const keyword = this.focusKeyword.toLowerCase();
            const keywordCount = (text.match(new RegExp(keyword, 'gi')) || []).length;
            const density = words.length > 0 ? (keywordCount / words.length) * 100 : 0;
            
            if (keywordCount === 0) {
                this.checks.keyword = { status: 'poor', message: 'Keyword not found in content' };
            } else if (density >= 1 && density <= 2) {
                this.checks.keyword = { status: 'good', message: `Perfect! Keyword appears ${keywordCount} times (${density.toFixed(2)}% density)` };
            } else if (density > 2) {
                this.checks.keyword = { status: 'warning', message: `Keyword appears ${keywordCount} times (${density.toFixed(2)}%) - might be keyword stuffing` };
            } else {
                this.checks.keyword = { status: 'warning', message: `Keyword appears ${keywordCount} times (${density.toFixed(2)}%) - use it more naturally` };
            }
        },

        analyzeHeadings() {
            const h1Count = (this.content.match(/<h1[^>]*>/gi) || []).length;
            const h2Count = (this.content.match(/<h2[^>]*>/gi) || []).length;
            const h3Count = (this.content.match(/<h3[^>]*>/gi) || []).length;
            
            const headingText = this.content.replace(/<h[1-3][^>]*>(.*?)<\/h[1-3]>/gi, '$1').toLowerCase();
            const hasKeywordInHeading = this.focusKeyword && headingText.includes(this.focusKeyword.toLowerCase());
            
            if (h1Count === 0 && h2Count === 0 && h3Count === 0) {
                this.checks.headings = { status: 'poor', message: 'No headings found - add H2 and H3 for better structure' };
            } else if (h2Count >= 2 && hasKeywordInHeading) {
                this.checks.headings = { status: 'good', message: `Great structure! ${h2Count} H2, ${h3Count} H3 with keyword` };
            } else if (h2Count >= 1) {
                if (hasKeywordInHeading) {
                    this.checks.headings = { status: 'warning', message: `${h2Count} H2, ${h3Count} H3 - add more subheadings` };
                } else {
                    this.checks.headings = { status: 'warning', message: `${h2Count} H2, ${h3Count} H3 - include keyword in headings` };
                }
            } else {
                this.checks.headings = { status: 'poor', message: 'Add H2 and H3 headings for better structure' };
            }
        },

        analyzeImages() {
            const imgTags = this.content.match(/<img[^>]*>/gi) || [];
            const totalImages = imgTags.length;
            const imagesWithAlt = imgTags.filter(img => /alt=["'][^"']*["']/.test(img)).length;
            const imagesWithoutAlt = totalImages - imagesWithAlt;
            
            if (totalImages === 0) {
                this.checks.images = { status: 'warning', message: 'No images found - consider adding at least 1 image' };
            } else if (imagesWithoutAlt === 0) {
                this.checks.images = { status: 'good', message: `Perfect! ${totalImages} images, all have alt text` };
            } else if (imagesWithAlt > 0) {
                this.checks.images = { status: 'warning', message: `${totalImages} images, ${imagesWithoutAlt} missing alt text` };
            } else {
                this.checks.images = { status: 'poor', message: `${totalImages} images, none have alt text - add descriptions` };
            }
        },

        analyzeLinks() {
            const allLinks = this.content.match(/<a[^>]*href=["']([^"']*)["'][^>]*>/gi) || [];
            const internalLinks = allLinks.filter(link => !link.includes('http') || link.includes(window.location.hostname)).length;
            const externalLinks = allLinks.length - internalLinks;
            
            if (allLinks.length === 0) {
                this.checks.links = { status: 'warning', message: 'No links found - add 2-3 internal links' };
            } else if (internalLinks >= 2 && externalLinks >= 1) {
                this.checks.links = { status: 'good', message: `Great! ${internalLinks} internal, ${externalLinks} external links` };
            } else if (internalLinks >= 1) {
                this.checks.links = { status: 'warning', message: `${internalLinks} internal, ${externalLinks} external - add more internal links` };
            } else {
                this.checks.links = { status: 'poor', message: `${allLinks.length} links - add internal links to related content` };
            }
        },

        calculateOverallScore() {
            const weights = {
                title: 20,
                content: 25,
                keyword: 25,
                headings: 15,
                images: 10,
                links: 5
            };
            
            const scores = {
                good: 1,
                warning: 0.6,
                poor: 0
            };
            
            let total = 0;
            for (const [key, weight] of Object.entries(weights)) {
                total += scores[this.checks[key].status] * weight;
            }
            
            this.overallScore = Math.round(total);
        },

        getScoreLabel() {
            if (this.overallScore >= 80) return 'Excellent SEO! Ready to publish 🎉';
            if (this.overallScore >= 50) return 'Good SEO - a few improvements needed ⚡';
            return 'Needs improvement - follow the suggestions above 📝';
        }
    };
}
</script>

<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\User;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();
        
        if (!$admin) {
            $admin = User::first();
        }

        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<h2>Welcome to Our Blog</h2>
                <p>We are passionate writers dedicated to sharing knowledge and insights with our readers. Our blog covers a wide range of topics including technology, lifestyle, and personal development.</p>
                
                <h3>Our Mission</h3>
                <p>Our mission is to provide high-quality, informative content that helps our readers learn, grow, and stay informed about the latest trends and developments in various fields.</p>
                
                <h3>Our Team</h3>
                <p>We have a diverse team of experienced writers and content creators who are experts in their respective fields. Each member brings unique perspectives and insights to our blog.</p>
                
                <h3>Contact Us</h3>
                <p>Have questions or suggestions? Feel free to reach out to us through our contact page or social media channels.</p>',
                'meta_description' => 'Learn more about our blog, our mission, and the team behind the content.',
                'meta_keywords' => 'about, team, mission, blog',
                'is_published' => true,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h2>Privacy Policy</h2>
                <p>Last updated: ' . date('F d, Y') . '</p>
                
                <h3>Information We Collect</h3>
                <p>We collect information that you provide directly to us, including:</p>
                <ul>
                    <li>Name and email address when you register</li>
                    <li>Profile information you choose to provide</li>
                    <li>Comments and content you post</li>
                    <li>Communications with us</li>
                </ul>
                
                <h3>How We Use Your Information</h3>
                <p>We use the information we collect to:</p>
                <ul>
                    <li>Provide, maintain, and improve our services</li>
                    <li>Send you technical notices and support messages</li>
                    <li>Respond to your comments and questions</li>
                    <li>Monitor and analyze trends and usage</li>
                </ul>
                
                <h3>Information Sharing</h3>
                <p>We do not share your personal information with third parties except as described in this policy or with your consent.</p>
                
                <h3>Data Security</h3>
                <p>We take reasonable measures to help protect your personal information from loss, theft, misuse, and unauthorized access.</p>
                
                <h3>Your Rights</h3>
                <p>You have the right to access, update, or delete your personal information at any time through your account settings.</p>
                
                <h3>Changes to This Policy</h3>
                <p>We may update this privacy policy from time to time. We will notify you of any changes by posting the new policy on this page.</p>
                
                <h3>Contact Us</h3>
                <p>If you have any questions about this privacy policy, please contact us.</p>',
                'meta_description' => 'Read our privacy policy to understand how we collect, use, and protect your personal information.',
                'meta_keywords' => 'privacy, policy, data protection, security',
                'is_published' => true,
            ],
            [
                'title' => 'Terms of Service',
                'slug' => 'terms-of-service',
                'content' => '<h2>Terms of Service</h2>
                <p>Last updated: ' . date('F d, Y') . '</p>
                
                <h3>Acceptance of Terms</h3>
                <p>By accessing and using this blog, you accept and agree to be bound by the terms and provision of this agreement.</p>
                
                <h3>Use License</h3>
                <p>Permission is granted to temporarily access the materials on this blog for personal, non-commercial use only.</p>
                
                <h3>User Content</h3>
                <p>Users are responsible for their own content and must ensure it does not violate any laws or infringe on the rights of others.</p>
                
                <h3>Prohibited Uses</h3>
                <p>You may not use this blog:</p>
                <ul>
                    <li>In any way that violates any applicable law or regulation</li>
                    <li>To transmit any advertising or promotional material</li>
                    <li>To impersonate or attempt to impersonate the blog owner or other users</li>
                    <li>To engage in any conduct that restricts or inhibits anyone\'s use of the blog</li>
                </ul>
                
                <h3>Disclaimer</h3>
                <p>The materials on this blog are provided on an "as is" basis. We make no warranties, expressed or implied, and hereby disclaim all other warranties.</p>
                
                <h3>Limitations</h3>
                <p>In no event shall we or our suppliers be liable for any damages arising out of the use or inability to use the materials on this blog.</p>
                
                <h3>Modifications</h3>
                <p>We may revise these terms of service at any time without notice. By using this blog, you agree to be bound by the current version of these terms.</p>',
                'meta_description' => 'Read our terms of service to understand the rules and guidelines for using our blog.',
                'meta_keywords' => 'terms, service, guidelines, rules',
                'is_published' => true,
            ],
            [
                'title' => 'Contact',
                'slug' => 'contact',
                'content' => '<h2>Get in Touch</h2>
                <p>We\'d love to hear from you! Whether you have a question, feedback, or just want to say hello, feel free to reach out.</p>
                
                <h3>Email Us</h3>
                <p>For general inquiries: <strong>info@example.com</strong></p>
                <p>For support: <strong>support@example.com</strong></p>
                
                <h3>Follow Us</h3>
                <p>Stay connected with us on social media:</p>
                <ul>
                    <li>Twitter: @ourblog</li>
                    <li>Facebook: /ourblog</li>
                    <li>Instagram: @ourblog</li>
                    <li>LinkedIn: /company/ourblog</li>
                </ul>
                
                <h3>Office Hours</h3>
                <p>Monday - Friday: 9:00 AM - 5:00 PM</p>
                <p>Saturday - Sunday: Closed</p>
                
                <h3>Response Time</h3>
                <p>We typically respond to all inquiries within 24-48 hours during business days.</p>',
                'meta_description' => 'Contact us for any questions, feedback, or support. We\'re here to help!',
                'meta_keywords' => 'contact, email, support, social media',
                'is_published' => true,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::create(array_merge($pageData, [
                'user_id' => $admin->id,
            ]));
        }
    }
}

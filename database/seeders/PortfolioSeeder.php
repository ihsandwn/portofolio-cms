<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $portfolios = [
            // AI / Agentic Entries (Extrapolated & New)
            [
                'title' => ['en' => 'RAG Knowledge retrieval System', 'id' => 'Sistem Knowledge Retrieval RAG'],
                'description' => ['en' => 'A retrieval-augmented generation pipeline using Solr and Vector Databases to provide accurate context for LLMs.', 'id' => 'Pipeline RAG menggunakan Solr dan Vector DB untuk konteks LLM yang akurat.'],
                'client' => 'Internal R&D',
                'type' => 'ai_agent',
                'tech_stack' => ['Python', 'LangChain', 'Solr', 'OpenAI', 'Pinecone'],
                'case_study' => "## Problem\nGeneric LLMs lack specific domain knowledge.\n\n## Solution\nLeveraged my experience with Solr (from SKCK Online) to build a hybrid search engine that retrieves key documents before feeding them to GPT-4.\n\n## Outcome\nReduced hallucination rates by 40% and improved answer relevance.",
                'meta_data' => ['model' => 'GPT-4o', 'vector_db' => 'Pinecone', 'latency' => '800ms'],
                'completed_at' => '2024-12-01',
                'is_featured' => true,
            ],
            [
                'title' => ['en' => 'Automated HR Screening Agent', 'id' => 'Agen Screening HR Otomatis'],
                'description' => ['en' => 'An autonomous agent that pre-screens resumes and matches them effectively against job descriptions.', 'id' => 'Agen otonom yang melakukan screening awal resume dan mencocokkannya dengan job description.'],
                'client' => 'Elabram Systems (Concept)',
                'type' => 'ai_agent',
                'tech_stack' => ['Laravel', 'OpenAI', 'Livewire', 'PostgreSQL'],
                'case_study' => "## Context\nBuilding on my HRIS experience, I designed an agentic workflow to automate the initial screening phase.\n\n## Implementation\nThe system parses PDF resumes, extracts key skills, and scores them against the JD using semantic matching.\n\n## Stack\nIntegrated directly into a Laravel queue worker for background processing.",
                'meta_data' => ['tokens_per_resume' => 1500, 'accuracy' => '85%'],
                'completed_at' => '2025-01-15',
                'is_featured' => true,
            ],

            [
                'title' => ['en' => 'Sentiment Analysis Agent (Alpha)', 'id' => 'Agen Analisis Sentimen (Alpha)'],
                'description' => ['en' => 'Real-time sentiment analysis of customer feedback using BERT and OpenAI.', 'id' => 'Analisis sentimen umpan balik pelanggan secara real-time menggunakan BERT dan OpenAI.'],
                'client' => 'Internal Lab',
                'type' => 'ai_agent',
                'tech_stack' => ['Python', 'BERT', 'FastAPI', 'React'],
                'case_study' => "## Overview\nProject Alpha focuses on understanding customer emotion at scale.\n\n## Demo\nInput: 'The service was terrible but the food was great.'\nOutput: Mixed (Service: Negative, Food: Positive).",
                'meta_data' => ['accuracy' => '92%', 'model' => 'BERT-Base'],
                'completed_at' => '2025-02-01',
                'is_featured' => true,
            ],
            [
                'title' => ['en' => 'PDF RAG Chatbot (Beta)', 'id' => 'Chatbot PDF RAG (Beta)'],
                'description' => ['en' => 'Chat with your PDF documents using a retrieval-augmented generation pipeline.', 'id' => 'Ngobrol dengan dokumen PDF Anda menggunakan pipeline RAG.'],
                'client' => 'Internal Lab',
                'type' => 'ai_agent',
                'tech_stack' => ['LangChain', 'Pinecone', 'OpenAI', 'Streamlit'],
                'case_study' => "## Overview\nProject Beta allows users to upload manuals and ask questions.\n\n## Architecture\nUses Pinecone for vector storage and GPT-4 for answer synthesis.",
                'meta_data' => ['context_window' => '16k', 'retrieval_speed' => '400ms'],
                'completed_at' => '2025-02-15',
                'is_featured' => true,
            ],

            // Core Web Dev / Architecture (From CV)
            [
                'title' => ['en' => 'SKCK Online Microservices', 'id' => 'Microservices SKCK Online'],
                'description' => ['en' => 'Architected a high-traffic microservices application for police record checks using Lumen, Vue.js, and Solr.', 'id' => 'Arsitektur aplikasi microservices trafik tinggi untuk SKCK menggunakan Lumen, Vue.js, dan Solr.'],
                'client' => 'PT Mitreka Indonesia',
                'url' => 'https://skck.polri.go.id',
                'type' => 'consulting', // Architecture fits consulting/specialist
                'tech_stack' => ['Lumen', 'Vue.js', 'MongoDB', 'Solr', 'Docker', 'Redis'],
                'case_study' => "## Architecture\nDesigned a distributed system to handle high concurrency. \n\n## Key Tech\n- **Solr**: For sub-second search queries across millions of records.\n- **Docker**: Reduced environment setup time by 40%.\n- **Gateway**: Express.js gateway for request aggregation.",
                'completed_at' => '2023-12-01',
                'is_featured' => false,
            ],
            [
                'title' => ['en' => 'Satu Data BKPM Portal', 'id' => 'Portal Satu Data BKPM'],
                'description' => ['en' => 'Led the development of a scalable open data portal for investment data.', 'id' => 'Memimpin pengembangan portal data terbuka yang scalable untuk data investasi.'],
                'client' => 'PT Mitreka Indonesia',
                'url' => 'https://data.bkpm.go.id',
                'type' => 'web',
                'tech_stack' => ['Laravel', 'PostgreSQL', 'CKan Integration'],
                'case_study' => "Built a user-friendly CMS to streamline access to investment data. Focused on data visualization and performance optimization using Laravel's caching mechanisms.",
                'completed_at' => '2024-06-01', // Estimated
                'is_featured' => true,
            ],
            [
                'title' => ['en' => 'HRIS & Job Portal Platform', 'id' => 'Platform HRIS & Job Portal'],
                'description' => ['en' => 'Engineered a comprehensive platform for recruitment tracking and HR management.', 'id' => 'Platform komprehensif untuk pelacakan rekrutmen dan manajemen SDM.'],
                'client' => 'PT Elabram Systems',
                'url' => 'https://job.elabram.com',
                'type' => 'web',
                'tech_stack' => ['Laravel', 'Vue.js', 'PostgreSQL'],
                'case_study' => "Optimized PostgreSQL queries to reduce data retrieval latency by 20%. Integrated role-based access control for secure HR data management.",
                'completed_at' => '2023-05-01',
                'is_featured' => false,
            ],
            [
                'title' => ['en' => 'Coal Shipping Backend System', 'id' => 'Sistem Backend Pengiriman Batubara'],
                'description' => ['en' => 'Developed backend systems for coal order tracking and supply chain transparency.', 'id' => 'Mengembangkan sistem backend untuk pelacakan pesanan batubara dan transparansi rantai pasok.'],
                'client' => 'PT Barito Integra Teknologi',
                'type' => 'web',
                'tech_stack' => ['Laravel', 'Angular', 'MySQL'],
                'case_study' => "Optimized MySQL performance, reducing query times by 20%. Enhanced supply chain transparency through secure APIs.",
                'completed_at' => '2020-06-01',
                'is_featured' => false,
            ],
            [
                'title' => ['en' => 'SBN Ritel Online', 'id' => 'SBN Ritel Online'],
                'description' => ['en' => 'Built a platform for ordering government securities with high transaction integrity.', 'id' => 'Platform pemesanan surat berharga negara dengan integritas transaksi tinggi.'],
                'client' => 'PT Lawencon (BRI)',
                'url' => 'https://sbn.bri.co.id',
                'type' => 'web',
                'tech_stack' => ['CodeIgniter', 'jQuery', 'Oracle'],
                'case_study' => "Designed efficient database structures and dashboards, improving transaction processing speed by 15%.",
                'completed_at' => '2018-10-01',
                'is_featured' => false,
            ],
        ];

        foreach ($portfolios as $portfolio) {
            Portfolio::create(array_merge($portfolio, [
                'slug' => Str::slug($portfolio['title']['en']),
            ]));
        }
    }
}

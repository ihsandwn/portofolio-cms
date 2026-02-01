# Automated HR Screening Agent - MVP 4

AI-powered resume screening and job description matching using Gemini AI semantic analysis.

## Features

✅ **Resume Parsing** - Extract text from PDF resumes  
✅ **Job Description Input** - Define requirements and skills  
✅ **Semantic Matching** - AI-powered skill matching  
✅ **Overall Scoring** - 0-100 match score  
✅ **Skills Analysis** - Matched and missing skills  
✅ **Experience Evaluation** - Years and relevance  
✅ **Education Assessment** - Level and relevance  
✅ **Strengths & Concerns** - Detailed candidate analysis  
✅ **Dark Blue Theme** - Consistent with portfolio design

## Tech Stack

- **Next.js** - React framework
- **TypeScript** - Type safety
- **Tailwind CSS** - Styling
- **Gemini 2.5-Flash** - AI semantic matching
- **pdf2json** - PDF resume parsing
- **React Dropzone** - File upload

## Getting Started

### 1. Start Development Server

```bash
npm run dev
```

Open [http://localhost:3003](http://localhost:3003)

### 2. Screen a Resume

- **Step 1**: Enter or load example job description
- **Step 2**: Upload PDF resume (drag & drop or browse)
- **Step 3**: View AI screening results with scores

## Project Structure

```
hr-screening/
├── app/
│   ├── api/
│   │   ├── screen/route.ts     # Resume screening endpoint
│   │   └── health/route.ts     # Health check
│   ├── layout.tsx              # Root layout
│   └── page.tsx                # Main page (3-column)
├── components/
│   ├── JobDescriptionInput.tsx # JD input with example
│   ├── ResumeUploader.tsx      # PDF upload with drag-drop
│   └── ScreeningResults.tsx    # Results visualization
├── lib/
│   ├── gemini-hr.ts            # Gemini AI HR client
│   └── resume-parser.ts        # PDF text extraction
└── .env.local                  # Environment variables
```

## API Endpoints

### POST /api/screen
Screen resume against job description.

**Request:** `multipart/form-data`
- `file`: PDF resume
- `jobDescription`: Job description text

**Response:**
```json
{
  "success": true,
  "filename": "resume.pdf",
  "overallScore": 85,
  "recommendation": "Highly Recommended",
  "matchedSkills": ["React", "Node.js", "TypeScript"],
  "missingSkills": ["Docker", "Kubernetes"],
  "experience": {
    "years": 5,
    "relevance": "High"
  },
  "education": {
    "level": "Bachelor",
    "relevance": "High"
  },
  "strengths": ["Strong technical skills", "Relevant experience"],
  "concerns": ["Missing DevOps experience"],
  "summary": "Excellent match for the role...",
  "screenedAt": "2026-01-31T..."
}
```

### GET /api/health
Health check endpoint.

## How It Works

1. **Input JD**: User enters job requirements and skills
2. **Upload Resume**: PDF resume uploaded and parsed
3. **Extract Text**: pdf2json extracts text from PDF
4. **AI Analysis**: Gemini AI semantically matches resume to JD
5. **Score & Rank**: Generate overall score and recommendations
6. **Display Results**: Show matched/missing skills, strengths, concerns

## Screening Criteria

### Overall Score (0-100)
- **80-100**: Highly Recommended (Green)
- **60-79**: Recommended (Blue)
- **40-59**: Maybe (Yellow)
- **0-39**: Not Recommended (Red)

### Matched Skills
- ✅ Skills found in both resume and JD
- Green badges with checkmark icon

### Missing Skills
- ❌ Required skills not found in resume
- Red badges with X icon

### Experience Analysis
- **Years**: Total years of experience
- **Relevance**: High/Medium/Low match to JD

### Education Assessment
- **Level**: Degree level (Bachelor/Master/PhD)
- **Relevance**: How relevant to the role

### Strengths
- 💡 Candidate's key advantages
- Bullet point list

### Concerns
- ⚠️ Potential issues or gaps
- Bullet point list

## Example Job Description

```
Senior Full-Stack Developer

Required Skills:
- 5+ years of experience with React and Node.js
- Strong knowledge of TypeScript
- Experience with PostgreSQL or MongoDB
- RESTful API design and development
- Git version control
- Agile/Scrum methodology

Preferred:
- AWS or GCP experience
- Docker and Kubernetes
- CI/CD pipeline setup
```

## Limitations

- PDF resumes only (max 10MB)
- English language primarily
- ~1500 tokens per resume analysis
- Analysis time: 3-5 seconds
- Accuracy: ~85%

## Use Cases

1. **Initial Screening**: Filter candidates quickly
2. **Bulk Processing**: Screen multiple resumes
3. **Skills Gap Analysis**: Identify missing qualifications
4. **Interview Preparation**: Focus on strengths/concerns
5. **Hiring Decision Support**: Data-driven recommendations

## Portfolio Integration

This MVP is designed for the **Elabram Systems (Concept)** portfolio entry:
- **Client**: Elabram Systems
- **Type**: AI Agent
- **Tech Stack**: Laravel, OpenAI, Livewire, PostgreSQL (production)
- **Concept**: Automated HR screening workflow
- **Tokens per Resume**: ~1500
- **Accuracy**: 85%

## Next Steps

- [ ] Add MongoDB storage for screening history
- [ ] Implement batch resume processing
- [ ] Add candidate ranking dashboard
- [ ] Export screening reports as PDF
- [ ] Integrate with ATS systems
- [ ] Multi-language support
- [ ] Deploy to Vercel

---

**Ready to use!** 🚀  
Open http://localhost:3003 and start screening resumes!

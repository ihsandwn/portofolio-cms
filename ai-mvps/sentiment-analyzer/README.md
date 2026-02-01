# Sentiment Analyzer - MVP 2

AI-powered sentiment analysis tool using Google Gemini AI to detect emotions and sentiment in text.

## Features

✅ **Text Analysis** - Analyze sentiment (Positive, Negative, Neutral)  
✅ **Emotion Detection** - Identify joy, sadness, anger, fear, surprise  
✅ **Confidence Scores** - See how confident the AI is about each emotion  
✅ **Real-time Results** - Get instant analysis with beautiful visualizations  
✅ **Example Texts** - Quick start with pre-written examples  
✅ **Dark Blue Theme** - Matches Laravel CMS color scheme

## Tech Stack

- **Next.js** - React framework
- **TypeScript** - Type safety
- **Tailwind CSS** - Styling
- **Gemini 2.5-Flash** - AI sentiment analysis
- **Recharts** - Data visualization (optional)
- **Lucide React** - Icons

## Getting Started

### 1. Start Development Server

```bash
npm run dev
```

Open [http://localhost:3001](http://localhost:3001)

### 2. Analyze Text

- Enter text in the textarea (up to 5000 characters)
- Or click an example to try it out
- Click "Analyze Sentiment"
- View results with sentiment, confidence, and emotions

## Environment Variables

Already configured in `.env.local`:

- `GEMINI_API_KEY` - Google Gemini API key
- `GEMINI_MODEL` - gemini-2.5-flash
- `MONGODB_URI` - MongoDB connection (for future features)
- `NEXT_PUBLIC_APP_URL` - http://localhost:3001

## Project Structure

```
sentiment-analyzer/
├── app/
│   ├── api/
│   │   ├── analyze/route.ts    # Sentiment analysis endpoint
│   │   └── health/route.ts     # Health check
│   ├── layout.tsx              # Root layout
│   └── page.tsx                # Main page
├── components/
│   ├── TextInput.tsx           # Text input with examples
│   └── ResultsDashboard.tsx    # Results visualization
├── lib/
│   └── gemini.ts               # Gemini AI client
└── .env.local                  # Environment variables
```

## API Endpoints

### POST /api/analyze
Analyze text sentiment and detect emotions.

**Request:**
```json
{
  "text": "I love this product!"
}
```

**Response:**
```json
{
  "success": true,
  "sentiment": "Positive",
  "confidence": 95,
  "emotions": {
    "joy": 85,
    "sadness": 5,
    "anger": 2,
    "fear": 3,
    "surprise": 5
  },
  "explanation": "Text expresses strong positive emotion...",
  "analyzedAt": "2026-01-31T..."
}
```

### GET /api/health
Health check endpoint.

## How It Works

1. **Input**: User enters text (up to 5000 characters)
2. **API Call**: Text sent to `/api/analyze` endpoint
3. **Gemini AI**: Gemini analyzes sentiment and emotions
4. **JSON Parsing**: Extract structured data from AI response
5. **Visualization**: Display results with progress bars and icons

## Features in Detail

### Sentiment Detection
- **Positive**: Happy, excited, satisfied
- **Negative**: Angry, sad, disappointed
- **Neutral**: Factual, informational

### Emotion Analysis
- **Joy**: Happiness, excitement
- **Sadness**: Disappointment, grief
- **Anger**: Frustration, rage
- **Fear**: Worry, anxiety
- **Surprise**: Shock, amazement

## Example Texts

1. **Positive**: "I absolutely love this product! It's amazing..."
2. **Negative**: "This is terrible. Worst experience ever..."
3. **Neutral**: "The weather is nice today. Going to the park."

## Limitations

- Max text length: 5000 characters
- Supports English primarily
- Emotions scored 0-100%
- Analysis time: 1-3 seconds

## Next Steps

- [ ] Add MongoDB storage for analysis history
- [ ] Implement batch text analysis
- [ ] Export results as PDF/CSV
- [ ] Multi-language support
- [ ] Deploy to Vercel

---

**Ready to use!** 🚀  
Open http://localhost:3001 and start analyzing sentiment!

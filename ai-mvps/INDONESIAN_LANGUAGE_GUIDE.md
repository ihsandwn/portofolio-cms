# Indonesian Language Support - Quick Implementation Guide

## What Was Added

✅ **Shared LanguageToggle Component** (`ai-mvps/shared/components/LanguageToggle.tsx`)
- Reusable EN/ID toggle button
- Clean UI with Languages icon
- Emits language change events

## How to Add to Each MVP

### Quick Steps for Each MVP:

1. **Copy the LanguageToggle component** to each MVP's `components/` folder
2. **Add language state** to the main page
3. **Pass language parameter** to API calls
4. **Update AI prompts** in API routes to request Indonesian when `lang=id`

### Implementation Pattern

```typescript
// In page.tsx
const [language, setLanguage] = useState<'en' | 'id'>('en');

// In API call
const response = await fetch('/api/endpoint', {
  method: 'POST',
  body: JSON.stringify({ ...data, language }),
});

// In API route
const languageInstruction = language === 'id' 
  ? 'PENTING: Jawab dalam Bahasa Indonesia yang natural dan profesional.'
  : 'Answer in English.';
  
const prompt = `${languageInstruction}\n\n${yourPrompt}`;
```

## Pre-configured for You

The LanguageToggle component is ready to use. Just:
1. Import it in each MVP
2. Add it to the header
3. Connect the `onLanguageChange` callback

## Example Usage

```typescript
import LanguageToggle from '@/components/LanguageToggle';

<LanguageToggle onLanguageChange={(lang) => {
  setLanguage(lang);
  // Optionally: reload current results with new language
}} />
```

---

**Note:** For production, consider using a proper i18n library like `next-intl` or `react-i18next` for comprehensive translation management.

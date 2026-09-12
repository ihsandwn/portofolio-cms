import PDFParser from 'pdf2json';

export interface ParsedPage {
  page: number;
  text: string;
}

interface PDFTextItem {
  R: Array<{ T: string }>;
}

interface PDFPage {
  Texts: PDFTextItem[];
}

interface PDFData {
  Pages: PDFPage[];
}

const PDF_MAGIC = Buffer.from('%PDF');

export function hasPdfMagicBytes(buffer: Buffer): boolean {
  return buffer.subarray(0, PDF_MAGIC.length).equals(PDF_MAGIC);
}

export async function extractTextFromPDF(buffer: Buffer): Promise<ParsedPage[]> {
  return new Promise((resolve, reject) => {
    const pdfParser = new PDFParser();
    const pages: ParsedPage[] = [];

    pdfParser.on('pdfParser_dataReady', (pdfData: PDFData) => {
      if (pdfData.Pages) {
        pdfData.Pages.forEach((page: PDFPage, i: number) => {
          let text = '';
          page.Texts.forEach((item: PDFTextItem) => {
            item.R.forEach((r: { T: string }) => {
              try { text += decodeURIComponent(r.T) + ' '; } catch { text += r.T + ' '; }
            });
          });
          pages.push({ page: i + 1, text });
        });
      }
      resolve(pages);
    });

    pdfParser.on('pdfParser_dataError', (errData: { parserError?: Error } | Error) => {
      const msg = errData instanceof Error ? errData.message : errData.parserError?.message ?? 'Unknown error';
      reject(new Error(`Failed to parse PDF: ${msg}`));
    });

    try { pdfParser.parseBuffer(buffer); } catch (e) { reject(e); }
  });
}

export function sanitizeText(text: string): string {
  return text
    .replace(/\s+/g, ' ')
    .replace(/\n\s*\n/g, '\n\n')
    .trim();
}

export function getTextPreview(text: string, maxLength = 500): string {
  if (text.length <= maxLength) return text;
  return text.slice(0, maxLength) + '...';
}

export const MAX_TEXT_LENGTH = 500_000;
export const MAX_PAGES = 1_000;
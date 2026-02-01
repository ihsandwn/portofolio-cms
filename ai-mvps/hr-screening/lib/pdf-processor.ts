// PDF Text Extraction using pdf2json
import PDFParser from 'pdf2json';

interface PDFTextItem {
    R: Array<{ T: string }>;
}

interface PDFPage {
    Texts: PDFTextItem[];
}

interface PDFData {
    Pages: PDFPage[];
}

export async function extractTextFromPDF(buffer: Buffer): Promise<string> {
    console.log('[PDF-PROCESSOR] Starting PDF extraction with pdf2json, buffer size:', buffer.length);

    return new Promise((resolve, reject) => {
        const pdfParser = new PDFParser();

        pdfParser.on('pdfParser_dataReady', (pdfData: PDFData) => {
            try {
                console.log('[PDF-PROCESSOR] PDF parsed successfully');

                // Extract text from all pages
                let fullText = '';

                if (pdfData.Pages) {
                    pdfData.Pages.forEach((page: PDFPage, pageIndex: number) => {
                        page.Texts.forEach((text: PDFTextItem) => {
                            text.R.forEach((r: { T: string }) => {
                                try {
                                    fullText += decodeURIComponent(r.T) + ' ';
                                } catch {
                                    // If decodeURIComponent fails, use the raw text
                                    fullText += r.T + ' ';
                                }
                            });
                        });
                        fullText += '\n\n';
                        console.log(`[PDF PROCESSOR] Page ${pageIndex + 1} extracted`);
                    });
                }

                console.log('[PDF-PROCESSOR] Success! Total text length:', fullText.length);
                resolve(fullText);
            } catch (error) {
                console.error('[PDF-PROCESSOR] Error processing PDF data:', error);
                reject(new Error(`Failed to process PDF: ${error instanceof Error ? error.message : String(error)}`));
            }
        });

        pdfParser.on('pdfParser_dataError', (errData: { parserError: Error } | Error) => {
            console.error('[PDF-PROCESSOR] PDF parsing error:', errData);
            const errorMessage = errData instanceof Error
                ? errData.message
                : errData.parserError?.message || 'Unknown error';
            reject(new Error(`Failed to parse PDF: ${errorMessage}`));
        });

        try {
            pdfParser.parseBuffer(buffer);
        } catch (error) {
            console.error('[PDF-PROCESSOR] Error initiating PDF parse:', error);
            reject(new Error(`Failed to initiate PDF parsing: ${error instanceof Error ? error.message : String(error)}`));
        }
    });
}

export function sanitizeText(text: string): string {
    // Remove excessive whitespace
    return text
        .replace(/\s+/g, ' ')
        .replace(/\n\s*\n/g, '\n\n')
        .trim();
}

export function getTextPreview(text: string, maxLength: number = 500): string {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
}

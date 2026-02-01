// PDF Resume Parser using pdf2json
import PDFParser from 'pdf2json';

export async function parseResume(buffer: Buffer): Promise<string> {
    console.log('[RESUME PARSER] Starting PDF extraction, buffer size:', buffer.length);

    return new Promise((resolve, reject) => {
        const pdfParser = new PDFParser();

        pdfParser.on('pdfParser_dataReady', (pdfData: any) => {
            try {
                console.log('[RESUME PARSER] PDF parsed successfully');

                let fullText = '';

                if (pdfData.Pages) {
                    pdfData.Pages.forEach((page: any, pageIndex: number) => {
                        page.Texts.forEach((text: any) => {
                            text.R.forEach((r: any) => {
                                try {
                                    fullText += decodeURIComponent(r.T) + ' ';
                                } catch (e) {
                                    // If decodeURIComponent fails, use the raw text
                                    fullText += r.T + ' ';
                                }
                            });
                        });
                        fullText += '\n\n';
                        console.log(`[RESUME PARSER] Page ${pageIndex + 1} extracted`);
                    });
                }

                console.log('[RESUME PARSER] Success! Total text length:', fullText.length);
                resolve(fullText);
            } catch (error) {
                console.error('[RESUME PARSER] Error processing PDF data:', error);
                reject(new Error(`Failed to process resume: ${error instanceof Error ? error.message : String(error)}`));
            }
        });

        pdfParser.on('pdfParser_dataError', (errData: any) => {
            console.error('[RESUME PARSER] PDF parsing error:', errData);
            reject(new Error(`Failed to parse resume: ${errData.parserError}`));
        });

        try {
            pdfParser.parseBuffer(buffer);
        } catch (error) {
            console.error('[RESUME PARSER] Error initiating PDF parse:', error);
            reject(new Error(`Failed to initiate PDF parsing: ${error instanceof Error ? error.message : String(error)}`));
        }
    });
}

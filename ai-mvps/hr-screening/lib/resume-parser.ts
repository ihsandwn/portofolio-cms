import PDFParser from 'pdf2json';

export const PDF_MAGIC_BYTES = Buffer.from('%PDF-');

interface PDFTextRun {
    T: string;
}

interface PDFTextItem {
    R: PDFTextRun[];
}

interface PDFPage {
    Texts: PDFTextItem[];
}

interface PDFData {
    Pages?: PDFPage[];
}

export function hasPdfMagicBytes(buffer: Buffer): boolean {
    return buffer.length >= PDF_MAGIC_BYTES.length
        && buffer.subarray(0, PDF_MAGIC_BYTES.length).equals(PDF_MAGIC_BYTES);
}

export async function parseResume(buffer: Buffer): Promise<string> {
    return new Promise((resolve, reject) => {
        const pdfParser = new PDFParser();

        pdfParser.on('pdfParser_dataReady', (pdfData: PDFData) => {
            try {
                const text = (pdfData.Pages ?? [])
                    .map((page) => page.Texts
                        .flatMap((item) => item.R)
                        .map((run) => {
                            try {
                                return decodeURIComponent(run.T);
                            } catch {
                                return run.T;
                            }
                        })
                        .join(' '))
                    .join('\n\n')
                    .replace(/\s+/g, ' ')
                    .trim();

                resolve(text);
            } catch (error) {
                reject(error instanceof Error ? error : new Error('PDF processing failed'));
            }
        });

        pdfParser.on('pdfParser_dataError', () => {
            reject(new Error('PDF parsing failed'));
        });

        try {
            pdfParser.parseBuffer(buffer);
        } catch {
            reject(new Error('PDF parsing failed'));
        }
    });
}

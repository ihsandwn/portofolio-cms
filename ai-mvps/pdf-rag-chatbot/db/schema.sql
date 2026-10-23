CREATE EXTENSION IF NOT EXISTS vector;

CREATE TABLE IF NOT EXISTS documents (
  id uuid PRIMARY KEY,
  filename text NOT NULL,
  owner_key text NOT NULL,
  uploaded_at timestamptz NOT NULL,
  expires_at timestamptz NOT NULL
);
CREATE TABLE IF NOT EXISTS document_chunks (
  document_id uuid NOT NULL REFERENCES documents(id) ON DELETE CASCADE,
  chunk_index integer NOT NULL,
  page integer NOT NULL,
  content text NOT NULL,
  embedding vector(3072) NOT NULL,
  PRIMARY KEY (document_id, chunk_index)
);
CREATE INDEX IF NOT EXISTS documents_expires_at_idx ON documents(expires_at);
-- ponytail: exact search per document; add an appropriate vector index when scale warrants it.

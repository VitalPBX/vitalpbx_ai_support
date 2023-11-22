#!/usr/bin/env python3

import os
import sys
from dotenv import load_dotenv
from langchain.document_loaders import PyPDFLoader
from langchain.document_loaders import Docx2txtLoader
from langchain.document_loaders import TextLoader
from langchain.embeddings import OpenAIEmbeddings
from langchain.vectorstores import Chroma
from langchain.text_splitter import CharacterTextSplitter

# Load environment variables from a .env file located at the specified path.
# This allows for secure and centralized configuration management.
load_dotenv("/var/lib/asterisk/agi-bin/.env")

# Retrieve paths and API keys from environment variables for further use.
# PATH_TO_DOCUMENTS: Directory containing documents to be processed.
# PATH_TO_DATABASE: Directory to store the processed document embeddings.
# OPENAI_API_KEY: API key for OpenAI services.
PATH_TO_DOCUMENTS = os.environ.get('PATH_TO_DOCUMENTS')
PATH_TO_DATABASE = os.environ.get('PATH_TO_DATABASE')
OPENAI_API_KEY = os.environ.get('OPENAI_API_KEY')

# Initialize an empty list to store documents loaded from files.
documents = []

# Loop through all files in the specified PATH_TO_DOCUMENTS directory.
# Depending on the file extension, appropriate loaders are used for PDF, DOCX, and TXT files.
for file in os.listdir(PATH_TO_DOCUMENTS):
    if file.endswith(".pdf"):
        # For PDF files, use PyPDFLoader to load the document.
        pdf_path = PATH_TO_DOCUMENTS + file
        loader = PyPDFLoader(pdf_path)
        documents.extend(loader.load())
    elif file.endswith('.docx') or file.endswith('.doc'):
        # For DOCX and DOC files, use Docx2txtLoader to load the document.
        doc_path = PATH_TO_DOCUMENTS + file
        loader = Docx2txtLoader(doc_path)
        documents.extend(loader.load())
    elif file.endswith('.txt'):
        # For TXT files, use TextLoader to load the document.
        text_path = PATH_TO_DOCUMENTS + file
        loader = TextLoader(text_path)
        documents.extend(loader.load())

# Split the loaded documents into smaller chunks for easier processing.
# CharacterTextSplitter is used to split based on character count, with a specified overlap.
text_splitter = CharacterTextSplitter(chunk_size=1000, chunk_overlap=10)
documents = text_splitter.split_documents(documents)

# Convert the document chunks into embeddings using OpenAIEmbeddings.
# These embeddings are then stored in a vector store (Chroma) for retrieval and analysis.
# The vector store is persisted in the specified PATH_TO_DATABASE.
vectordb = Chroma.from_documents(documents, embedding=OpenAIEmbeddings(), persist_directory=PATH_TO_DATABASE)
vectordb.persist()

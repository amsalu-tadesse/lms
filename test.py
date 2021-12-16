import pdftotext

# Load PDF file
with open("uploads/pdf/law.pdf", "rb") as f:
    pdf = pdftotext.PDF(f)

# If it's password-protected
#with open("secure_pdffile.pdf", "rb") as f:
 #   pdf = pdftotext.PDF(f, "secret")

# Iterate over all the pages
for page in pdf:
    # text content in pdf page
    print(page)

# Read all the text into one string
print("\n\n".join(pdf))


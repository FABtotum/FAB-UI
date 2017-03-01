from distutils.core import setup, Extension

setup(name='file_utils',
      version='1.0',
      ext_modules=[Extension('file_utils', ['file_utils.c'])],
)

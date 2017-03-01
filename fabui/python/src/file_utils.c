#include <Python.h>

#include <stdio.h>
#include <stdint.h>
#include <stdbool.h>
#include <string.h>

#define BUFFER_SIZE 1<<16

PyObject* subpgm_lines_dict;

void parse_line(char* saddr, uint32_t* linecnt, PyObject* dict)
{
    static uint32_t subpgm_lines = 0;
    static uint32_t subpgm_idx = 0;
    static bool in_subpgm = false;

    uint32_t scratch; 

    if (in_subpgm)
        subpgm_lines++;
    else
        (*linecnt)++;

    if (*saddr == 'O' && in_subpgm == false) {
        scratch = strtoul(saddr+1, NULL, 10);
        if (scratch >= 0) {
            subpgm_idx = scratch;
            in_subpgm = true;
        }
    }
    else if (*saddr == 'M') {
        switch (strtoul(saddr+1, NULL, 10)) {
            case 98: {
                if (in_subpgm == true)
                    return;

                char* param;
                uint32_t m98_subpgm_idx = 0;
                uint32_t m98_subpgm_rep = 1;

                param = strchr(saddr, 'P');
                if (param == NULL) {
                    return;
                }
                scratch = strtoul(param+1, NULL, 10);
                if (scratch > 0) {
                    m98_subpgm_idx = scratch;
                }

                param = strchr(saddr, 'L');
                if (param != NULL) {
                    scratch = strtoul(param+1, NULL, 10);
                    if (scratch >= 0) {
                        m98_subpgm_rep = scratch;
                    }
                }

                if (PyDict_Contains(dict, PyInt_FromLong(m98_subpgm_idx)) > 0)
                {
                    PyObject* pyitem = PyDict_GetItem(dict, PyInt_FromLong(m98_subpgm_idx));
                    if (pyitem > 0) {
                        *linecnt += (PyInt_AsLong(pyitem) * m98_subpgm_rep);
                    }
                }
                break;
            }
            case 99: {
                if (in_subpgm == false)
                    return;

                PyDict_SetItem(
                    dict,
                    PyInt_FromLong(subpgm_idx),
                    PyInt_FromLong(subpgm_lines-1)
                );
                subpgm_lines = 0;
                in_subpgm = false;
                break;
            }
        }
    }
}

static PyObject* file_len_impl(PyObject* self, PyObject* args)
{
    FILE* fp;
    const char* filename;
    uint32_t lines = 0;

    if (!PyArg_ParseTuple(args, "s", &filename))
        return NULL;

    fp = fopen(filename, "rb");
    if (fp == NULL) {
        PyErr_SetString(PyExc_IOError, "[Errno 2] No such file or directory.");
        return (PyObject *)NULL;
    }

    char buf[BUFFER_SIZE];
    char* saddr;
    uint32_t buffered_chars = 0;
    uint32_t last_valid_line = 0;
    PyObject* subpgm_lines_dict = PyDict_New();

    while (!feof(fp)) {
        uint32_t i;
        bool waiting_lf = false;
        bool valid_line = false;

        last_valid_line = 0;
        buffered_chars = fread(buf, 1, BUFFER_SIZE, fp);
        saddr = buf;
        for (i = 0; i < buffered_chars; i++) {
            if (waiting_lf) {
                // CR-only: Empty line
                if (buf[i] == '\r') {
                    buf[i] = '\0';
                    valid_line = true;
                }
                // CRLF: skip character
                else if (buf[i] == '\n') {
                    last_valid_line++;
                    saddr++;
                }
                waiting_lf = false;
            }
            else {
                // CR-only or CRLF line
                if (buf[i] == '\n' || buf[i] == '\r') {
                    if (buf[i] == '\r')
                        waiting_lf = true;
                    buf[i] = '\0';
                    valid_line = true;
                }
            }

            if (valid_line) {
                parse_line(saddr, &lines, subpgm_lines_dict);
                valid_line = false;
                last_valid_line = i+1;
                saddr = &buf[i+1];
            }
        }

        if (!feof(fp) && last_valid_line > 0)
            fseek(fp, -(buffered_chars-last_valid_line), SEEK_CUR);
    }

    if (last_valid_line < buffered_chars) {
        uint32_t remsize = buffered_chars-last_valid_line;
        char brem[remsize+1];
        memcpy(brem, buf+last_valid_line, remsize);
        brem[remsize] = '\0';
        parse_line(brem, &lines, subpgm_lines_dict);
    }

    fclose(fp);
    Py_DECREF(subpgm_lines_dict);
    return Py_BuildValue("k", lines);
}

static PyMethodDef Methods[] = {
    {"file_len", file_len_impl, METH_VARARGS, ""},
    {NULL, NULL, 0, NULL}
};

PyMODINIT_FUNC
initfile_utils(void)
{
    (void)Py_InitModule("file_utils", Methods);
}




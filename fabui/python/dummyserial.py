class Serial(object):
    def __init__(self, *args, **kwargs):
        try:
            self._timeout = kwargs["timeout"]
        except KeyError:
            self._timeout = 10
        self._opened = True
        self.queue = []

    def inWaiting(self):
        return True if len(self.queue) else False

    def write(self, s):
        self.queue.append(s)

    def readline(self):
        tokens = self.queue.pop(0).rsplit("*", 1)[0].split(" ")

        if "M105" in tokens:
            return "ok T:%.1f /%.1f B:%.1f /%.1f" % (
                temperatures["extruder_target"],
                temperatures["extruder_target"],
                temperatures["bed_target"],
                temperatures["bed_target"]
            )
        else:
            return "ok"

    def isOpen(self):
        return self._opened

    def close(self):
        self._opened = False
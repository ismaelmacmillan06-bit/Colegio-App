import time
from smartcard.System import readers
from smartcard.util import toHexString

def leer_uid():
    r = readers()
    if not r:
        print("ERROR_NO_LECTOR")
        return

    lector = r[0]
    for _ in range(20):
        try:
            connection = lector.createConnection()
            connection.connect()
            GET_UID = [0xFF, 0xCA, 0x00, 0x00, 0x00]
            response, sw1, sw2 = connection.transmit(GET_UID)
            connection.disconnect()
            if sw1 == 0x90:
                uid = toHexString(response).replace(" ", "").upper()
                print(uid)
                return
        except:
            pass
        time.sleep(0.5)

    print("ERROR_TIMEOUT")

leer_uid()
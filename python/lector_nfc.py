import time
import requests
from smartcard.System import readers
from smartcard.util import toHexString

URL_API = "http://127.0.0.1:8000/api/asistencia/registrar"

def leer_uid(connection):
    GET_UID = [0xFF, 0xCA, 0x00, 0x00, 0x00]
    response, sw1, sw2 = connection.transmit(GET_UID)
    if sw1 == 0x90:
        return toHexString(response).replace(" ", "").upper()
    return None

def registrar_asistencia(uid):
    try:
        response = requests.post(URL_API, json={"nfc_uid": uid}, timeout=5)
        data = response.json()
        tipo = data.get('tipo', '')
        persona = data.get('persona', '')
        nombre = data.get('nombre', '')
        hora = data.get('hora', '')
        mensaje = data.get('mensaje', '')

        if persona == 'alumno':
            icono = '🎒'
        elif persona == 'docente':
            icono = '👨‍🏫'
        else:
            icono = '👤'

        if tipo == 'entrada':
            print(f"\n{icono} ENTRADA registrada")
        elif tipo == 'salida':
            print(f"\n{icono} SALIDA registrada")
        else:
            print(f"\nℹ️  {mensaje}")

        print(f"   Nombre: {nombre}")
        if hora:
            print(f"   Hora:   {hora}")
        print("-" * 40)

    except Exception as e:
        print(f"✗ Error al conectar con el servidor: {e}")

def main():
    print("=" * 40)
    print("  Sistema de Asistencia NFC")
    print("  Colegio Digital")
    print("=" * 40)
    print("Esperando credencial...\n")

    ultimo_uid = None
    ultimo_tiempo = 0

    while True:
        try:
            r = readers()
            if not r:
                print("✗ Lector no detectado. Reconectando...")
                time.sleep(2)
                continue

            lector = r[0]
            connection = lector.createConnection()
            connection.connect()

            uid = leer_uid(connection)

            if uid:
                ahora = time.time()
                if uid != ultimo_uid or (ahora - ultimo_tiempo) > 5:
                    print(f"Credencial: {uid}")
                    registrar_asistencia(uid)
                    ultimo_uid = uid
                    ultimo_tiempo = ahora

            connection.disconnect()

        except Exception:
            pass

        time.sleep(0.5)

if __name__ == "__main__":
    main()
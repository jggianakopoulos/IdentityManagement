import argparse
parser = argparse.ArgumentParser()
parser.add_argument("face", type=string)
parser.add_argument("new_face", type=string)

face = args.face
new_face = args.new_face

print(face, new_face)